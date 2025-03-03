from fastapi import FastAPI
from pydantic import BaseModel
from transformers import AutoModelForSequenceClassification, AutoTokenizer, pipeline
import uvicorn
import pickle

# Define API app
app = FastAPI(title="Disease Prediction API", version="1.0", description="Predict diseases based on symptoms.")

# Load the fine-tuned model and tokenizer
MODEL_PATH = "model/fine_tuned_pubmedbert"  # Update this with your actual model path
model = AutoModelForSequenceClassification.from_pretrained(MODEL_PATH)
tokenizer = AutoTokenizer.from_pretrained(MODEL_PATH)

# Load label encoder (to decode predicted labels)
with open("model/label_encoder.pkl", "rb") as f:
    label_encoder = pickle.load(f)

# Define a classifier pipeline
classifier = pipeline("text-classification", model=model, tokenizer=tokenizer)

# Request Body Schema
class SymptomInput(BaseModel):
    symptoms: str  # Example: "I have a persistent cough, fever, and chills."

@app.post("/predict")
def predict_disease(input_data: SymptomInput):
    # Perform prediction
    result = classifier(input_data.symptoms)

    # Extract label index from "LABEL_X"
    predicted_label_index = int(result[0]["label"].replace("LABEL_", ""))
    
    # Convert index to disease name
    predicted_disease = label_encoder.inverse_transform([predicted_label_index])[0]

    return {"predicted_disease": predicted_disease}


@app.get("/")
def home():
    return {"message": "Disease Prediction API is running!"}

# Main function to run FastAPI server
def main():
    uvicorn.run(app, host="0.0.0.0", port=8000)

# Run the app when the script is executed directly
if __name__ == "__main__":
    main()
