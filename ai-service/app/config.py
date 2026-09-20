import os
from pydantic import BaseModel

class Settings(BaseModel):
    APP_NAME: str = "JobMatch AI - Microservice IA & Ingestion"
    API_V1_STR: str = "/api/v1"
    PORT: int = 8000
    HOST: str = "127.0.0.1"
    
    # Clés API Optionnelles (si absentes, le service bascule en mode mock intelligent)
    ANTHROPIC_API_KEY: str = os.getenv("ANTHROPIC_API_KEY", "")
    GEMINI_API_KEY: str = os.getenv("GEMINI_API_KEY", "")
    VOYAGE_API_KEY: str = os.getenv("VOYAGE_API_KEY", "")
    FRANCE_TRAVAIL_CLIENT_ID: str = os.getenv("FRANCE_TRAVAIL_CLIENT_ID", "")
    FRANCE_TRAVAIL_CLIENT_SECRET: str = os.getenv("FRANCE_TRAVAIL_CLIENT_SECRET", "")

settings = Settings()
