const menuItemsAfterLogin = [
  { title: "Templates", page: "moovly-templates" },
  { title: "Projects", page: "moovly-projects" },
  { title: "Post Videos", page: "moovly-post-videos" },
];

export const removeLoggedInMenuItems = () =>
  menuItemsAfterLogin.forEach((item) => {
    const anchor = document.querySelector(
      `[href="admin.php?page=${item.page}"]`
    );
    if (anchor) {
      try {
        anchor.parentNode.remove();
      } catch (e) {}
    }
  });

export const addLoggedInMenuItems = () => {
  const submenu = document.querySelector("#toplevel_page_moovly .wp-submenu");
  menuItemsAfterLogin.forEach((item) => {
    const li = document.createElement("li");
    const a = document.createElement("a");
    a.innerHTML = item.title;
    a.href = `admin.php?page=${item.page}`;
    li.appendChild(a);
    submenu.appendChild(li);
  });
};
