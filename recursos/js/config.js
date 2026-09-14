window.APP_BASE = (() => {
  const baseTag = document.querySelector("base");
  if (baseTag && baseTag.getAttribute("href")) {
    return new URL(
      baseTag.getAttribute("href"),
      window.location.href,
    ).pathname.replace(/\/+$/, "");
  }

  const currentPath = window.location.pathname;
  const basePath = currentPath.includes("index.php")
    ? currentPath.substring(0, currentPath.lastIndexOf("/"))
    : currentPath;

  return basePath.replace(/\/+$/, "");
})();

window.appUrl = function (path) {
  const normalized = path.startsWith("/") ? path : `/${path}`;
  return `${window.APP_BASE || ""}${normalized}`;
};
