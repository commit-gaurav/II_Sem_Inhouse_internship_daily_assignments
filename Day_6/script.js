// Grab references to the HTML elements we'll need to update
const loadingEl = document.getElementById("loading");
const errorEl = document.getElementById("error");
const cardContainer = document.getElementById("card-container");

// Runs once the whole page (HTML) has loaded
document.addEventListener("DOMContentLoaded", () => {
  fetchPosts();
});

// Fetches posts from JSONPlaceholder and handles loading/error/success
async function fetchPosts() {
  try {
    const response = await fetch("https://jsonplaceholder.typicode.com/posts");

    // fetch() only rejects on network failure, not on bad status codes,
    // so we check response.ok ourselves
    if (!response.ok) {
      throw new Error("Server responded with status " + response.status);
    }

    const posts = await response.json();

    renderCards(posts);

  } catch (err) {
    showError(err.message);

  } finally {
    // Runs whether it succeeded or failed
    loadingEl.classList.add("d-none");
  }
}

// Builds one Bootstrap card per post and adds them to the page
function renderCards(posts) {
  posts.forEach((post) => {
    const cardHTML = `
      <div class="col-md-4">
        <div class="card post-card">
          <div class="card-body">
            <h5 class="card-title">${post.title}</h5>
            <p class="card-text">${post.body}</p>
          </div>
        </div>
      </div>
    `;
    cardContainer.insertAdjacentHTML("beforeend", cardHTML);
  });
}

// Displays an error message instead of the cards
function showError(message) {
  errorEl.textContent = "Failed to load posts: " + message;
  errorEl.classList.remove("d-none");
}
