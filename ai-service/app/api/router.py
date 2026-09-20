from fastapi import APIRouter, UploadFile, File, HTTPException, Body
from typing import List, Optional
from app.api.schemas import (
    CVParseResponse, CVStructuredData, MatchingBatchRequest, MatchingBatchResponse,
    LetterGenerateRequest, LetterGenerateResponse, CollectRequest, CollectResponse
)
from app.llm.parser import extract_text_from_file, parse_cv_text
from app.llm.letter_generator import generate_cover_letter_and_tips
from app.matching.engine import evaluate_opportunity
from app.collectors.rss_collector import collect_from_rss
from app.collectors.france_travail_collector import collect_from_france_travail

api_router = APIRouter()

@api_router.get("/health")
def health_check():
    return {
        "status": "healthy",
        "service": "JobMatch AI - Microservice IA & Ingestion",
        "version": "1.1.0"
    }

@api_router.post("/cv/parse", response_model=CVParseResponse)
async def parse_cv(file: UploadFile = File(...)):
    """Reçoit un fichier CV (PDF ou DOCX), extrait le texte et le structure via IA."""
    try:
        content_bytes = await file.read()
        raw_text = extract_text_from_file(file.filename, content_bytes)
        
        if not raw_text or len(raw_text.strip()) < 10:
            raise HTTPException(status_code=400, detail="Impossible d'extraire le contenu textuel du document.")
            
        data, embedding = parse_cv_text(raw_text)
        return CVParseResponse(
            success=True,
            data=data,
            embedding=embedding,
            raw_text_length=len(raw_text)
        )
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Erreur lors du traitement du CV: {str(e)}")

@api_router.post("/matching/score", response_model=MatchingBatchResponse)
def compute_matching(payload: MatchingBatchRequest):
    """Calcule le score de pertinence et applique les filtres durs pour une liste d'opportunités."""
    results = []
    for opp in payload.opportunites:
        res = evaluate_opportunity(
            cv_data=payload.cv_data,
            cv_embedding=payload.cv_embedding,
            opp=opp,
            filters=payload.filters
        )
        results.append(res)
        
    # Tri des résultats par score de pertinence décroissant
    results.sort(key=lambda x: (x.passed_hard_filters, x.score_pertinence), reverse=True)
    return MatchingBatchResponse(success=True, results=results)

@api_router.post("/applications/generate-letter", response_model=LetterGenerateResponse)
def generate_letter(payload: LetterGenerateRequest):
    """Génère une lettre de motivation personnalisée et des conseils d'adaptation ATS."""
    try:
        return generate_cover_letter_and_tips(payload)
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Erreur de génération : {str(e)}")

@api_router.post("/collect/run", response_model=CollectResponse)
def run_collection(payload: CollectRequest):
    """Lance la collecte d'offres depuis les sources configurées (RSS ou France Travail)."""
    if payload.source_type == "france_travail":
        offers = collect_from_france_travail(mots_cles=payload.category or "développeur", max_results=payload.max_results or 15)
        if not offers:
            # Fallback vers RSS si les identifiants France Travail ne sont pas encore définis
            offers = collect_from_rss(feed_url=payload.target_url, max_results=payload.max_results or 15)
    else:
        offers = collect_from_rss(feed_url=payload.target_url, max_results=payload.max_results or 15)
        
    return CollectResponse(success=True, count=len(offers), offers=offers)
