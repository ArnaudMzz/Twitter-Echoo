function autoRefresh(minutes) {
  const interval = minutes * 60000; // Convert minutes to milliseconds
  setInterval(() => {
    window.location.reload();
  }, interval);
}

//Auto-refresh every 2 minutes
autoRefresh(2);
