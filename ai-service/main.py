from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from app.config import settings
from app.api.router import api_router

app = FastAPI(
    title=settings.APP_NAME,
    description="Microservice d'ingestion d'offres, de parsing de CVs, de matching vectoriel sémantique et de génération IA.",
    version="1.1.0",
    docs_url="/docs",
    redoc_url="/redoc"
)

# Configuration CORS pour autoriser les requêtes venant du Frontend Vue.js et de Laravel
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Enregistrement des routes d'API
app.include_router(api_router, prefix=settings.API_V1_STR)
app.include_router(api_router) # Supporte aussi les routes directes sans préfixe pour flexibilité

@app.get("/")
def root():
    return {
        "message": "Bienvenue sur l'API JobMatch AI (Microservice IA & Ingestion)",
        "version": "1.1.0",
        "documentation": "/docs",
        "endpoints": [
            "/api/v1/health",
            "/api/v1/cv/parse",
            "/api/v1/matching/score",
            "/api/v1/applications/generate-letter",
            "/api/v1/collect/run"
        ]
    }

if __name__ == "__main__":
    import uvicorn
    uvicorn.run("main:app", host=settings.HOST, port=settings.PORT, reload=True)
