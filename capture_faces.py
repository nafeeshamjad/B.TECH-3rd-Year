import cv2
import os

# Ask for student ID (you can use roll no)
student_id = input("Enter Student ID or Roll No: ")

# Create folder for this student
dataset_dir = "dataset"
student_dir = os.path.join(dataset_dir, student_id)

os.makedirs(student_dir, exist_ok=True)

face_cascade = cv2.CascadeClassifier(
    cv2.data.haarcascades + "haarcascade_frontalface_default.xml"
)

cam = cv2.VideoCapture(0)

count = 0  # number of images saved

while True:
    ret, frame = cam.read()
    if not ret:
        break

    gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
    faces = face_cascade.detectMultiScale(gray, 1.3, 5)

    for (x, y, w, h) in faces:
        # Draw rectangle
        cv2.rectangle(frame, (x, y), (x+w, y+h), (255, 0, 0), 2)

        # Crop face region
        face_roi = gray[y:y+h, x:x+w]

        # Save face image every few frames
        if count < 50:  # capture 50 images
            img_path = os.path.join(student_dir, f"{count}.jpg")
            cv2.imwrite(img_path, face_roi)
            count += 1
            print(f"Saved {img_path}")

    cv2.imshow("Capturing Faces - Press q to quit", frame)

    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

cam.release()
cv2.destroyAllWindows()

print("Done! Captured images for student:", student_id)
