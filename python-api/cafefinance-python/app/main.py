
import os

import psycopg2
from fastapi import FastAPI, HTTPException
from psycopg2.extras import RealDictCursor

app = FastAPI()

def db_connection():
    return psycopg2.connect(
        host=os.getenv("DB_HOST", "postgres"),
        port=os.getenv("DB_PORT", "5432"),
        dbname=os.getenv("DB_NAME", "app_db"),
        user=os.getenv("DB_USER", "app_user"),
        password=os.getenv("DB_PASSWORD", "app_pass"),
    )

@app.get("/")
def home():
    return {
        "status": "success",
        "message": "Python API funcionando",
        "endpoints": [
            "/health",
            "/users",
        ],
    }

@app.get("/health")
def health():
    try:
        with db_connection() as connection:
            with connection.cursor() as cursor:
                cursor.execute("SELECT 1")

        return {
            "status": "ok",
            "database": "connected",
        }
    except psycopg2.Error as error:
        raise HTTPException(
            status_code=500,
            detail=f"Erro ao conectar no banco: {error}",
        ) from error

@app.get("/users")
def list_users():
    try:
        with db_connection() as connection:
            with connection.cursor(cursor_factory=RealDictCursor) as cursor:
                cursor.execute("SELECT id, name, email FROM users ORDER BY id")
                users = cursor.fetchall()

        return {
            "status": "success",
            "data": users,
        }
    except psycopg2.Error as error:
        raise HTTPException(
            status_code=500,
            detail=f"Erro ao consultar usuários: {error}",
        ) from error
