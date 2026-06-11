import os
import cv2
import numpy as np
import mysql.connector
from datetime import datetime, date
from flask import Flask, jsonify

# -------------------- Flask App --------------------
app = Flask(__name__)

# -------------------- MySQL Database Configuration --------------------
# Make sure this matches your phpMyAdmin DB
def get_db_connection():
    return mysql.connector.connect(
        host="localhost",
        user="root",
        password="",              # keep empty if you don't use a password
        database="attendance_system"
    )

# -------------------- Load trained model & face detector --------------------
BASE_DIR = os.path.dirname(os.path.abspath(__file__))

# recognizer.yml inside: C:\xampp\htdocs\attendance_project\python\models\recognizer.yml
MODEL_PATH = os.path.join(BASE_DIR, "models", "recognizer.yml")

if not os.path.exists(MODEL_PATH):
    raise FileNotFoundError(f"Model file not found at: {MODEL_PATH}")

recognizer = cv2.face.LBPHFaceRecognizer_create()
recognizer.read(MODEL_PATH)

detector = cv2.CascadeClassifier(cv2.data.haarcascades + "haarcascade_frontalface_default.xml")


# -------------------- Attendance function --------------------
def mark_attendance(user_id, db, cursor):
    # 1) Check if this user_id actually exists in users table
    cursor.execute("SELECT id FROM users WHERE id = %s", (user_id,))
    user_row = cursor.fetchone()

    if user_row is None:
        print(f"[WARN] Predicted user_id {user_id} does NOT exist in users table. Skipping attendance.")
        return

    today = date.today()
    now = datetime.now()
    current_time = now.strftime("%H:%M:%S")

    # 2) Check if attendance already marked today
    cursor.execute("""
        SELECT * FROM attendance
        WHERE user_id = %s AND date = %s
    """, (user_id, today))

    result = cursor.fetchone()

    if result is None:
        cursor.execute("""
            INSERT INTO attendance (user_id, date, time, status)
            VALUES (%s, %s, %s, 'Present')
        """, (user_id, today, current_time))

        db.commit()
        print(f"[INFO] Attendance marked for user_id: {user_id} at {current_time}")
    else:
        print(f"[INFO] Attendance already marked today for user_id: {user_id}")


# -------------------- Recognition Function --------------------
def start_recognition():
    # New DB connection for this run
    db = get_db_connection()
    cursor = db.cursor()

    cam = cv2.VideoCapture(0)
    cam.set(3, 640)
    cam.set(4, 480)

    print("Starting Face Recognition... Press 'q' to stop.")

    while True:
        ret, frame = cam.read()
        if not ret:
            print("[WARN] Failed to grab frame from camera.")
            continue

        gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
        faces = detector.detectMultiScale(gray, scaleFactor=1.2, minNeighbors=5)

        for (x, y, w, h) in faces:
            face_img = gray[y:y+h, x:x+w]

            # recognizer returns a label; we are treating it as user_id
            label, confidence = recognizer.predict(face_img)
            user_id = int(label)

            print(f"[DEBUG] Predicted label/user_id: {user_id}, confidence: {confidence}")

            # Draw bounding box + ID
            cv2.rectangle(frame, (x, y), (x+w, y+h), (0, 255, 0), 2)
            cv2.putText(frame, f"User ID: {user_id}", (x, y-10),
                        cv2.FONT_HERSHEY_SIMPLEX, 0.8, (0, 255, 0), 2)

            # Mark attendance only when match is good
            if confidence < 70:
                mark_attendance(user_id, db, cursor)

        cv2.imshow("Face Recognition Attendance", frame)

        if cv2.waitKey(1) & 0xFF == ord('q'):
            break

    cam.release()
    cv2.destroyAllWindows()
    cursor.close()
    db.close()
    print("[INFO] Recognition stopped, DB connection closed.")


# -------------------- API Endpoint to Start Recognition --------------------
@app.route('/start', methods=['GET'])
def start_endpoint():
    try:
        start_recognition()
        return jsonify({"success": True, "message": "Face recognition finished. Attendance updated."})
    except Exception as e:
        # Print error in console and return as JSON
        print(f"[ERROR] {e}")
        return jsonify({"success": False, "message": str(e)}), 500


# -------------------- Run Flask Server --------------------
if __name__ == '__main__':
    print("Flask server running at http://127.0.0.1:5000/start")
    # host 0.0.0.0 so you can also call from other devices in same network if needed
    app.run(host='0.0.0.0', port=5000, debug=False)
