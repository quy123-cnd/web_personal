const menuLinks = document.querySelectorAll("nav a[href^='#']");

const contentLinks = document.querySelectorAll(
	"nav a[href^='#'], main a[href^='#']"
);

const headerContent = document.querySelector("main > header");

const welcomeContent = document.querySelector("main > .welcome");

const statsContent = document.querySelector("main > .stats");

const panelContents = document.querySelectorAll("main > .panel");

const imageFileInput = document.getElementById("album-image-file");

const imageUrlInput = document.getElementById("image-url");

const imageUrlsInput = document.getElementById("image-urls");

const imagePreview = document.getElementById("image-preview");

const uploadStatus = document.getElementById("upload-status");

if (imageFileInput) {

	imageFileInput.addEventListener("change", function () {

		const selectedFiles = Array.from(imageFileInput.files);

		if (selectedFiles.length === 0) {

			return;
		}

		uploadStatus.textContent =
			"Đang tải " + selectedFiles.length + " ảnh lên Cloudinary...";

		const uploadPromises = selectedFiles.map(function (selectedFile) {

			const formData = new FormData();

			formData.append("file", selectedFile);

			formData.append(
				"upload_preset",
				window.cloudinaryConfig.uploadPreset
			);

			return fetch(
				"https://api.cloudinary.com/v1_1/" +
				window.cloudinaryConfig.cloudName +
				"/image/upload",
				{
					method: "POST",
					body: formData
				}
			)
				.then(function (response) {

					return response.json().then(function (data) {

						if (!response.ok) {

							throw new Error(
								data.error?.message ||
								"Cloudinary trả về lỗi HTTP " + response.status
							);
						}

						return data.secure_url;
					});
				});
		});

		Promise.all(uploadPromises)
			.then(function (imageUrls) {

				imageUrlInput.value = imageUrls[0];

				imageUrlsInput.value = JSON.stringify(imageUrls);

				imagePreview.src = imageUrls[0];

				imagePreview.hidden = false;

				uploadStatus.textContent =
					"Đã tải " + imageUrls.length + " ảnh lên Cloudinary.";
			})
			.catch(function (error) {

				console.error("Cloudinary upload error:", error);

				uploadStatus.textContent = "Lỗi Cloudinary: " + error.message;
			});
	});
}

function hideAllContents() {

	headerContent.hidden = true;

	welcomeContent.hidden = true;

	statsContent.hidden = true;

	panelContents.forEach(function (panel) {

		panel.hidden = true;
	});
}

function showContent(targetId) {

	hideAllContents();

	if (targetId === "tong-quan") {

		headerContent.hidden = false;

		welcomeContent.hidden = false;

		statsContent.hidden = false;

	} else {

		const targetPanel = document.getElementById(targetId);

		if (targetPanel) {

			targetPanel.hidden = false;
		}
	}
}

function updateActiveMenu(targetId) {

	menuLinks.forEach(function (link) {

		const isActive = link.getAttribute("href") === "#" + targetId;

		link.classList.toggle("active", isActive);
	});
}

function handleContentLink(event) {

	event.preventDefault();

	const targetId = event.currentTarget.getAttribute("href").substring(1);

	showContent(targetId);

	updateActiveMenu(targetId);

	history.replaceState(null, "", "#" + targetId);
}

contentLinks.forEach(function (link) {

	link.addEventListener("click", handleContentLink);
});

const initialTarget = window.location.hash.substring(1) || "tong-quan";

showContent(initialTarget);

updateActiveMenu(initialTarget);