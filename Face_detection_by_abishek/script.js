const video = document.getElementById("video");

// Enhanced logging and error handling
async function initializeWebcam() {
  try {
    // Check for webcam support
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
      throw new Error("Webcam access not supported in this browser");
    }

    // Detailed media constraints
    const constraints = {
      video: {
        width: { ideal: 640 },
        height: { ideal: 480 },
        facingMode: "user", // prefer front camera
      },
      audio: false,
    };

    // Request webcam access
    const stream = await navigator.mediaDevices.getUserMedia(constraints);

    // Set video source and play
    video.srcObject = stream;

    // Additional event listeners for debugging
    video.addEventListener("loadedmetadata", () => {
      console.log("Video metadata loaded");
    });

    video.addEventListener("canplay", () => {
      console.log("Video can play");
      video.play(); // Explicitly call play
    });

    video.addEventListener("error", (e) => {
      console.error("Video error:", e);
    });
  } catch (error) {
    console.error("Webcam access error:", error);
    alert(`Webcam access failed: ${error.message}`);
  }
}

// Load models and initialize webcam
async function loadModelsAndInitializeWebcam() {
  try {
    console.log("Loading models...");
    await Promise.all([
      faceapi.nets.tinyFaceDetector.loadFromUri("./models"),
      faceapi.nets.faceLandmark68Net.loadFromUri("./models"),
      faceapi.nets.faceRecognitionNet.loadFromUri("./models"),
      faceapi.nets.faceExpressionNet.loadFromUri("./models"),
      faceapi.nets.ageGenderNet.loadFromUri("./models"),
    ]);
    console.log("Models loaded successfully");

    // Initialize webcam after models are loaded
    await initializeWebcam();
  } catch (error) {
    console.error("Model loading error:", error);
  }
}

// Start everything when page loads
window.addEventListener("load", loadModelsAndInitializeWebcam);

// Face detection setup
video.addEventListener("play", () => {
  console.log("Video play event triggered");

  const canvas = faceapi.createCanvasFromMedia(video);
  document.body.append(canvas);

  // Positioning canvas
  canvas.style.position = "absolute";
  canvas.style.top = `${video.offsetTop}px`;
  canvas.style.left = `${video.offsetLeft}px`;

  const displaySize = { width: video.width, height: video.height };
  faceapi.matchDimensions(canvas, displaySize);

  // Detection interval
  setInterval(async () => {
    try {
      const detections = await faceapi
        .detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
        .withFaceLandmarks()
        .withFaceExpressions()
        .withAgeAndGender();

      canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);

      const resizedDetections = faceapi.resizeResults(detections, displaySize);

      faceapi.draw.drawDetections(canvas, resizedDetections);
      faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
      faceapi.draw.drawFaceExpressions(canvas, resizedDetections);

      resizedDetections.forEach((detection) => {
        const box = detection.detection.box;
        const drawBox = new faceapi.draw.DrawBox(box, {
          label: `${Math.round(detection.age)} year old ${detection.gender}`,
        });
        drawBox.draw(canvas);
      });
    } catch (error) {
      console.error("Detection error:", error);
    }
  }, 100);
});
