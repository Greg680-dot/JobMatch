import io
from fastapi.testclient import TestClient
from main import app

client = TestClient(app)

def test_health():
    response = client.get("/api/v1/health")
    assert response.status_code == 200
    assert response.json()["status"] == "healthy"
    print("[PASS] Test Health Check")

def test_collect():
    response = client.post("/api/v1/collect/run", json={"source_type": "rss", "max_results": 5})
    assert response.status_code == 200
    data = response.json()
    assert data["success"] is True
    assert data["count"] > 0
    print(f"[PASS] Test Collecte d'offres : {data['count']} offres collectées.")
    return data["offers"]

def test_cv_parse():
    sample_cv = """
    Alexandre Martin
    alexandre.martin@example.com
    06 12 34 56 78
    Développeur Full Stack Senior
    
    Profil :
    Ingénieur logiciel passionné avec 5 ans d'expérience dans la conception d'architectures web robustes.
    
    Compétences :
    PHP, Laravel, Vue.js, Python, FastAPI, Docker, PostgreSQL, MySQL, Git, Tailwind, Linux
    
    Expériences :
    - Développeur Full Stack chez SaaS Corp (2022 - 2026) : Développement d'APIs REST en Laravel et interfaces réactives avec Vue.js 3.
    - Développeur Backend chez WebAgency (2020 - 2022) : Conception de microservices Python et bases relationnelles PostgreSQL.
    
    Formations :
    Master Informatique - Université Paris-Saclay (2020)
    """
    file_bytes = sample_cv.encode('utf-8')
    response = client.post(
        "/api/v1/cv/parse",
        files={"file": ("cv_alexandre.txt", io.BytesIO(file_bytes), "text/plain")}
    )
    assert response.status_code == 200
    res = response.json()
    assert res["success"] is True
    assert "Laravel" in res["data"]["competences_techniques"] or "Python" in res["data"]["competences_techniques"]
    print(f"[PASS] Test Parsing CV : {res['data']['nom']} ({len(res['data']['competences_techniques'])} compétences détectées)")
    return res["data"], res["embedding"]

def test_matching(cv_data, cv_embedding, offers):
    payload = {
        "cv_data": cv_data,
        "cv_embedding": cv_embedding,
        "filters": {
            "types_contrat": ["CDI", "FREELANCE"],
            "keywords_excluded": ["COBOL", "JAVA"]
        },
        "opportunites": offers
    }
    response = client.post("/api/v1/matching/score", json=payload)
    assert response.status_code == 200
    res = response.json()
    assert res["success"] is True
    assert len(res["results"]) > 0
    top = res["results"][0]
    print(f"[PASS] Test Matching : Meilleur score = {top['score_pertinence']}% | {top['summary_explanation']}")

def test_letter_generation(cv_data):
    payload = {
        "candidate_profile": cv_data,
        "job_title": "Développeur Full Stack Laravel & Vue.js",
        "company_name": "InnoTech Solutions",
        "job_description": "Nous cherchons un expert Laravel et Vue.js avec Docker et PostgreSQL.",
        "matching_skills": ["Laravel", "Vue.js", "PostgreSQL"],
        "tone": "Professionnel et percutant"
    }
    response = client.post("/api/v1/applications/generate-letter", json=payload)
    assert response.status_code == 200
    res = response.json()
    assert res["success"] is True
    assert "InnoTech Solutions" in res["cover_letter"]
    assert len(res["cv_adaptation_tips"]) > 0
    print("[PASS] Test Génération Lettre & Conseils ATS validé")

if __name__ == "__main__":
    print("=== Démarrage des Tests Automatisés du Service IA ===")
    test_health()
    offers = test_collect()
    cv_data, cv_embedding = test_cv_parse()
    test_matching(cv_data, cv_embedding, offers)
    test_letter_generation(cv_data)
    print("=== TOUS LES TESTS DU SERVICE IA SONT AU VERT [OK] ===")
