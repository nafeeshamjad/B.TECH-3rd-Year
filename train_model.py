import os
import cv2
import numpy as np

# Correct paths (relative to this script)
DATASET_PATH = "dataset"
MODEL_PATH = "models/recognizer.yml"

def get_images_and_labels(dataset_path):
    face_samples = []
    ids = []

    detector = cv2.CascadeClassifier(cv2.data.haarcascades + "haarcascade_frontalface_default.xml")

    for folder_name in os.listdir(dataset_path):
        folder_path = os.path.join(dataset_path, folder_name)

        # Only process folders
        if not os.path.isdir(folder_path):
            continue

        try:
            # Folder name MUST be the same as users.id (e.g., "1", "2", "5")
            student_id = int(folder_name)
        except ValueError:
            print(f"[WARN] Skipping folder '{folder_name}' (not a number)")
            continue

        for img_name in os.listdir(folder_path):
            img_path = os.path.join(folder_path, img_name)

            img = cv2.imread(img_path, cv2.IMREAD_GRAYSCALE)
            if img is None:
                print(f"[WARN] Could not read image: {img_path}")
                continue

            faces = detector.detectMultiScale(img)

            for (x, y, w, h) in faces:
                face_samples.append(img[y:y + h, x:x + w])
                ids.append(student_id)

    return face_samples, ids


def train_model():
    print("Training model... Please wait...")

    faces, ids = get_images_and_labels(DATASET_PATH)

    if len(faces) == 0:
        print("❌ No face images found in dataset! Please capture faces first.")
        return

    print(f"[INFO] Number of face samples: {len(faces)}")
    print(f"[INFO] Unique IDs found in dataset: {sorted(set(ids))}")

    recognizer = cv2.face.LBPHFaceRecognizer_create()

    # IMPORTANT: use 32-bit integers for labels
    recognizer.train(faces, np.array(ids, dtype=np.int32))

    recognizer.write(MODEL_PATH)

    print("✔ Model trained successfully!")
    print(f"✔ Saved as: {MODEL_PATH}")


if __name__ == "__main__":
    train_model()
