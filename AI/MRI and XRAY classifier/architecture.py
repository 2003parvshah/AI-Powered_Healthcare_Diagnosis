import torch
import torchvision.models as models
import torch.nn as nn

class BrainTumorPlusLungXrayArchitecture(nn.Module):
    def __init__(self, num_classes=4):
        super(BrainTumorPlusLungXrayArchitecture, self).__init__()

        self.backbone = models.efficientnet_b0(weights=models.EfficientNet_B0_Weights.DEFAULT)

        self.backbone.features[0][0] = nn.Conv2d(1, 32, kernel_size=3, stride=2, padding=1, bias=False)

        for param in self.backbone.parameters():
            param.requires_grad = False

        for param in self.backbone.features[-3:].parameters():
            param.requires_grad = True

        self.classifier = nn.Sequential(
            nn.Dropout(0.25),
            nn.Linear(1280, num_classes)
        )

    def forward(self, x):
        x = self.backbone.features(x)
        x = self.backbone.avgpool(x)
        x = torch.flatten(x, 1)
        x = self.classifier(x)
        return x

device = torch.device("cuda" if torch.cuda.is_available() else "cpu")
braintumor_model = BrainTumorPlusLungXrayArchitecture(num_classes=4).to(device)
lungxray_model = BrainTumorPlusLungXrayArchitecture(num_classes=4).to(device)

braintumor_model.load_state_dict(torch.load("brain_tumor_pytorch_model.pth", map_location=device))
lungxray_model.load_state_dict(torch.load("lung_xray-pytorch_model.pth", map_location=device))

braintumor_model.eval()
lungxray_model.eval()