import io
import torch
import torch.nn.functional as F
import torchvision.transforms as transforms
from PIL import Image
from fastapi import FastAPI, File, UploadFile, HTTPException
from pydantic import BaseModel
from architecture import braintumor_model, lungxray_model, device

app = FastAPI()

# Define class labels for both models
brain_tumor_labels = ["Glioma", "Meningioma", "No Tumor", "Pituitary"]
lung_xray_labels = ["Corona Virus Disease", "Normal", "Pneumonia", "Tuberculosis"]

# Request Body Model
class PredictionRequest(BaseModel):
    type: str   # User must specify "mri" or "xray"

# Image Preprocessing Function
def preprocess_image(image: Image.Image):
    transform = transforms.Compose([
        transforms.Grayscale(num_output_channels=1),
        transforms.Resize((224, 224)),
        transforms.ToTensor(),
        transforms.Normalize([0.5], [0.5])
    ])
    image = transform(image).unsqueeze(0)
    return image.to(device)

# Prediction Function
def predict(image: Image.Image, model_type:str):
    # Select the correct model based on user input
    if model_type.lower() == "mri":
        model, class_labels = braintumor_model, brain_tumor_labels
    elif model_type.lower() == "xray":
        model, class_labels = lungxray_model, lung_xray_labels
    else:
        raise HTTPException(status_code=400, detail="Invalid type. Use 'mri' or 'xray'.")
    
    # Preprocess and make perdiction
    image_tensor = preprocess_image(image)
    model.eval()
    with torch.no_grad():
        output = model(image_tensor)
        probabilities = F.softmax(output, dim=1)
        predicted_class = torch.argmax(probabilities, dim=1).item()
        confidence = probabilities[0][predicted_class].item() * 100

    return {
        "Detected Type": model_type.upper(),
        "Predicted Class": class_labels[predicted_class],
        "Confidence": f"{confidence:.2f}%"
    }

@app.post("/predict_image")
async def upload_image(file: UploadFile = File(...), model_type: str = "mri"):
    try:
        image = Image.open(io.BytesIO(await file.read()))
        result = predict(image, model_type)
        return {
            "filename": file.filename,
            "prediction": result 
        }
    except Exception as e:
        return {"error": str(e)}