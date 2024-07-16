<!DOCTYPE html>
<html lang="en">
<head>
  <title>Employer Dashboard</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
  <style>


body{
  font-family: 'Roboto', sans-serif;
  font-family: 'Poppins', sans-serif;
}

.stars {
      color: #ffc107;
      font-size: 30px;
    }

</style>
</head>
<body>
  

  <main class=" justify-center">

    <div class=" mx-auto">
        <span class="text-blue-500" style="font-size: 25px; font-weight: bold;">JOB ORBER HISTORY</span>
        
        <div class="bg-blue-100 p-6 rounded-lg" style="width: 100%;">
            
            <h1 class="text-lg font-bold text-blue-500">Delivery Helper</h1>
            <p class="text-sm text-gray-600">06/01/2024</p>
            <button class="bg-yellow-500 text-white font-bold py-2 px-4 rounded-full text-lg" style="height: 28px; padding-top: 1px;">
                COMPLETED
            </button>
            <div class="mt-4">
                <p class="font-bold text-gray-700">Employed Worker: <span class="text-green-500">JOSEPH BERSOTO</span></p>
            </div>

            <hr class="my-4">

            <h2 class="text-xl font-bold text-gray-900">Job details</h2>

            <div class="flex items-center mt-2 text-gray-600">
                <i class="fas fa-briefcase mr-2"></i>
                <p class="text-sm">Full-time</p>
            </div>
            <div class="flex items-center mt-2 text-gray-600">
                <i class="fas fa-map-marker-alt mr-2"></i>
                <p class="text-sm">Quezon City</p>
            </div>

            <div class="mt-4">
                <h3 class="text-lg font-bold text-gray-900">Salary</h3>
                <p class="text-sm bg-gray-100 px-2 py-1 rounded text-gray-700 font-semibold">PHP 15,000 - PHP 18,000</p>
            </div>

            <hr class="my-4">

            <h2 class="text-xl font-bold text-gray-900">Full Job description</h2>
            <p class="text-sm mt-2 text-gray-600">Responsibilities:</p>
            <ul class="list-disc list-inside text-sm text-gray-600 mt-2 mb-4">
                <li>Assist the primary driver in the delivery and unloading of products to various locations.</li>
                <li>Ensure timely and accurate delivery of goods, following the assigned route and schedule.</li>
                <li>Load and unload products from the delivery vehicle safely.</li>
                <li>Verify delivery items against invoices or shipping documents.</li>
                <li>Provide excellent customer service by addressing client inquiries and concerns professionally.</li>
                <li>Maintain cleanliness and organization of the delivery vehicle.</li>
                <li>Follow all safety protocols and company procedures during deliveries.</li>
                <li>Assist in the regular maintenance and inspection of the delivery vehicle.</li>
            </ul>
            <div class="mb-4">
                <h2 class="font-bold text-lg">Qualifications:</h2>
                <ul class="list-disc list-inside text-sm text-gray-600 mt-2 mb-4">
                    <li>Valid driver’s license with a clean driving record.</li>
                    <li>Ability to lift and carry heavy items (up to 50 lbs) regularly.</li>
                    <li>Good communication and interpersonal skills.</li>
                    <li>Ability to work flexible hours, including weekends and holidays.</li>
                    <li>Previous delivery or customer service experience is a plus.</li>
                </ul>
            </div>

            <div class="mb-4">
                <h2 class="font-bold text-lg">Review:</h2>
                <!-- <div class="stars">
                    ★★★★☆
                </div> -->
                <p class="text-sm bg-gray-100 mt-2 text-gray-600">
                    Joseph was very careful with handling the products that were being transported.
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
                    Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>
            </div>
        </div>
    </div>
  </main>
  <script>
    document.getElementById('acceptButton').addEventListener('click', function() {
        document.getElementById('confirmationBox').classList.remove('hidden');
    });

    document.getElementById('confirmButton').addEventListener('click', function() {
        document.getElementById('confirmationBox').classList.add('hidden');
        window.location.href = "employergivingfeedback.html";
    });

    document.getElementById('cancelButton').addEventListener('click', function() {
        document.getElementById('confirmationBox').classList.add('hidden');
    });
  </script>
</body>
</html>
