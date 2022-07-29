const menuItemsAfterLogin = {
  Templates: "moovly-templates",
  Projects: "moovly-projects",
  "Post Videos": "moovly-post-videos",
};

export const removeLoggedInMenuItems = () => {
  Object.values(menuItemsAfterLogin).forEach((page) => {
    const anchor = document.querySelector(`[href="admin.php?page=${page}"]`);
    if (anchor) {
      try {
        anchor.parentNode.remove();
      } catch (e) {}
    }
  });
};

export const addLoggedInMenuItems = () => {
  const submenu = document.querySelector("#toplevel_page_moovly .wp-submenu");
  Object.keys(menuItemsAfterLogin).forEach((page) => {
    const li = document.createElement("li");
    const a = document.createElement("a");
    a.innerHTML = page;
    a.href = `admin.php?page=${page}`;
    li.appendChild(a);
    submenu.appendChild(li);
  });
};
