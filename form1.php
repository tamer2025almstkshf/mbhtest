<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Form 1 - Contact</title>
</head>
<body>
  <h2>Form 1: Contact</h2>
  <form id="form1">
    <input type="text" name="name" placeholder="Name"><br><br>
    <input type="date" name="dob" placeholder="Date of Birth"><br><br>
    <textarea name="message" placeholder="Message"></textarea><br><br>
    <button type="submit">Submit</button>
    <button type="button" onclick="saveFormDraft('form1', 'message')">Save Draft</button>
  </form>

  <div id="notifications"></div>

  <script>
const DRAFT_STORAGE_KEY = "form_drafts";

/**
 * Load all saved drafts
 */
function getAllDrafts() {
  try {
    const raw = localStorage.getItem("form_drafts");
    const parsed = JSON.parse(raw);
    return Array.isArray(parsed) ? parsed : [];
  } catch (e) {
    return [];
  }
}


/**
 * Save a new draft (with unique ID)
 */
function saveFormDraft(formId, formTitleField = "message") {
  const form = document.getElementById(formId);
  const inputs = Array.from(form.elements).filter(e => e.name);
  const data = {};
  inputs.forEach(el => data[el.name] = el.value);

  const title = data[formTitleField] || "Untitled Draft";

  const newDraft = {
    id: Date.now().toString(), // Unique ID
    path: window.location.pathname,
    formId,
    title,
    data,
    createdAt: new Date().toISOString()
  };

  const drafts = getAllDrafts();
  drafts.push(newDraft);
  localStorage.setItem(DRAFT_STORAGE_KEY, JSON.stringify(drafts));
}

/**
 * Remove draft by ID
 */
function removeDraftById(id) {
  const drafts = getAllDrafts().filter(d => d.id !== id);
  localStorage.setItem(DRAFT_STORAGE_KEY, JSON.stringify(drafts));
}

/**
 * Show all draft notifications (on every page)
 */
function showDraftNotifications(containerId = "notifications") {
  const container = document.getElementById(containerId);
  if (!container) return;

  container.innerHTML = "";
  const drafts = getAllDrafts();

  drafts.forEach(draft => {
    const note = document.createElement("div");
    note.textContent = `📝 ${draft.title} (Saved from ${draft.path})`;
    note.style.background = "#fef3c7";
    note.style.padding = "10px";
    note.style.margin = "5px 0";
    note.style.cursor = "pointer";
    note.style.borderRadius = "6px";
    note.dataset.draftId = draft.id;

    note.onclick = () => {
      // Redirect with the draft ID in the URL
      window.location.href = `${draft.path}?draftId=${draft.id}`;
    };

    container.appendChild(note);
  });
}

/**
 * If draftId exists in URL, load that draft
 */
function loadDraftFromURL() {
  const urlParams = new URLSearchParams(window.location.search);
  const draftId = urlParams.get("draftId");
  if (!draftId) return;

  const draft = getAllDrafts().find(d => d.id === draftId);
  if (!draft) return;

  const form = document.getElementById(draft.formId);
  if (!form) return;

  for (const name in draft.data) {
    if (form[name]) {
      form[name].value = draft.data[name];
    }
  }
}
</script>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      showDraftNotifications();
      loadDraftFromURL();
    });

    document.getElementById('form1').addEventListener('submit', (e) => {
      e.preventDefault();
      alert("Form submitted!");
      // Optionally, clear the draft from localStorage here
    });
  </script>
</body>
</html>
