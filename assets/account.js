console.log(window.purchaseHistory);

const historyTable = document.querySelector('.purchase-history table');

purchaseHistory.forEach(order => {
  const row = document.createElement('tr');
  row.innerHTML = `
      <td>${order.orderId}</td>
      <td>${order.date}</td>
      <td>${order.total}</td>
      <td>${order.status}</td>
  `;
  historyTable.appendChild(row);
});

//Tracking Table
console.log(window.orderTracking);

const trackingTable = document.querySelector('.tracking-result table');

orderTracking.forEach(track => {
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>${track.orderId}</td>
        <td><center><img src="${track.imageUrl}" alt="${track.product}" width="100"></center></td>
        <td>${track.product}</td>
        <td>${track.status}</td>
        <td>${track.date}</td>
        <td><center><button class="view-details-btn">Track</button></center></td>
    `;
    trackingTable.appendChild(row);
});




document.getElementById('sign-out-btn').addEventListener('click', () => {
  // Clear session or localStorage data
  localStorage.clear();  // Assuming user data is stored in localStorage

  // Redirect to home or login page
  window.location.href = 'signup.php';  // Change to your login or home page URL
});

