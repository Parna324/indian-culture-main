<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "config.php"; // Include your DB connection

// Explicit connection check
if ($conn === false) {
    die("DATABASE CONNECTION FAILED! Check config.php details (especially password). Script halted. Error: " . mysqli_connect_error());
}
echo "Database Connection OK!<br><hr>";

// --- Automatically Fix/Create the Database Table ---
echo "Attempting to fix/create the 'state_visits' table...<br>";

// SQL to drop the table if it exists
$sql_drop = "DROP TABLE IF EXISTS state_visits;";
if (mysqli_query($conn, $sql_drop)) {
    echo "- Old 'state_visits' table dropped (if existed).<br>";
} else {
    echo "<strong style='color:red;'>- Error dropping table: " . mysqli_error($conn) . "</strong><br>";
}

// SQL to create the table correctly
$sql_create = "CREATE TABLE state_visits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    state_name VARCHAR(100) NOT NULL UNIQUE,
    best_time VARCHAR(255),
    description TEXT,
    culture TEXT
);";

if (mysqli_query($conn, $sql_create)) {
    echo "- New 'state_visits' table created successfully.<br>";
} else {
    die("<strong style='color:red;'>- CRITICAL ERROR creating table: " . mysqli_error($conn) . ". Script halted.</strong>");
}
echo "Table setup complete.<br><hr>";

echo "Attempting to populate states...<br>";
echo "<pre>"; // For readable output

// --- State Data Array (from previous step) ---
$state_visit_info = [
    // North India
    "Delhi" => [
        "best_time" => "October to March (Winter)",
        "description" => "Pleasant cool weather, ideal for exploring historical monuments, markets, and parks.",
        "culture" => "Major festivals like Diwali (Oct/Nov) and Republic Day parade (Jan). Avoid extreme summer heat (Apr-Jun) and heavy fog (Dec-Jan)."
    ],
    "Haryana" => [
        "best_time" => "September to March (Autumn/Winter)",
        "description" => "Comfortable weather for visiting places like Kurukshetra and Sultanpur Bird Sanctuary.",
        "culture" => "Lohri (Jan) is celebrated. Good time for exploring agricultural landscapes."
    ],
     "Himachal Pradesh" => [
        "best_time" => "March to June (Summer - hills pleasant), September to November (Autumn - clear views)",
        "description" => "Ideal for trekking, visiting Shimla, Manali, Dharamshala. Summer offers escape from plains' heat; Autumn has crisp air.",
        "culture" => "Various local fairs. Dussehra in Kullu (Oct) is famous. Avoid peak monsoon (Jul-Aug) and heavy winter snow (Dec-Feb) in higher reaches."
    ],
     "Jammu and Kashmir" => [
        "best_time" => "April to June (Summer - Srinagar, Gulmarg pleasant), September to October (Autumn - mild)",
        "description" => "Summer is ideal for Srinagar's Dal Lake, Gulmarg meadows. Autumn offers pleasant weather before winter.",
        "culture" => "Shikara rides, Tulip Garden (Spring). Winter (Dec-Feb) for skiing in Gulmarg but heavy snow elsewhere."
    ],
     "Ladakh" => [
        "best_time" => "May to September (Summer)",
        "description" => "Roads are open, sunny days. Ideal for Pangong Lake, Nubra Valley, monasteries. High altitude region.",
        "culture" => "Monastery festivals like Hemis Tsechu (Jun/Jul). Requires acclimatization. Winter is extremely harsh."
    ],
    "Punjab" => [
        "best_time" => "October to March (Winter)",
        "description" => "Pleasant weather for visiting the Golden Temple in Amritsar and exploring cities.",
        "culture" => "Baisakhi (Apr) and Lohri (Jan) are major festivals. Best time to experience Punjabi culture and cuisine."
    ],
    "Rajasthan" => [
        "best_time" => "October to March (Winter)",
        "description" => "Cool days and chilly nights, perfect for exploring forts (Jaipur, Jodhpur, Jaisalmer), palaces, and deserts without the extreme heat.",
        "culture" => "Peak tourist season. Many cultural festivals like Pushkar Camel Fair (Nov), Jaipur Literature Festival (Jan), and Desert Festival (Feb) take place."
    ],
    "Uttarakhand" => [
        "best_time" => "March to June (Summer - hills pleasant), September to November (Autumn)",
        "description" => "Good for trekking, yoga retreats (Rishikesh), wildlife (Jim Corbett), and hill stations (Nainital, Mussoorie).",
        "culture" => "Char Dham Yatra season opens (Apr/May). Ganga Aarti in Haridwar/Rishikesh year-round. Avoid heavy monsoons."
    ],
     "Uttar Pradesh" => [
        "best_time" => "October to March (Winter)",
        "description" => "Cool and pleasant weather, perfect for visiting the Taj Mahal in Agra, exploring Lucknow, and experiencing Varanasi's ghats.",
        "culture" => "Ideal time for religious tourism in Varanasi and Mathura. Major festivals like Diwali (Oct/Nov) and Holi (Mar) are celebrated grandly."
    ],
    // East & Northeast India
    "Arunachal Pradesh" => [
        "best_time" => "October to April (Winter/Spring)",
        "description" => "Clear skies and pleasant weather for exploring Tawang monastery and scenic landscapes. Requires permits.",
        "culture" => "Various tribal festivals like Losar (Feb/Mar), Ziro Music Festival (Sep). Diverse indigenous cultures."
    ],
    "Assam" => [
        "best_time" => "October to April (Winter/Spring)",
        "description" => "Pleasant weather, ideal for Kaziranga National Park (rhino sightings), Majuli island, and tea gardens.",
        "culture" => "Bihu festivals (especially Rongali Bihu in Apr) are major highlights. Rich tribal culture and handicrafts."
    ],
    "Bihar" => [
        "best_time" => "October to March (Winter)",
        "description" => "Comfortable weather for exploring historical sites like Bodh Gaya, Nalanda, and Patna.",
        "culture" => "Chhath Puja (Oct/Nov) is a major festival. The weather is suitable for visiting pilgrimage sites comfortably."
    ],
    "Jharkhand" => [
        "best_time" => "October to February (Winter)",
        "description" => "Pleasant climate for visiting waterfalls, hills (Netarhat), and tribal villages.",
        "culture" => "Rich tribal culture, Sarhul festival (Spring). Known for forests and mineral resources."
    ],
     "Manipur" => [
        "best_time" => "October to April (Winter/Spring)",
        "description" => "Pleasant weather for visiting Loktak Lake and exploring Imphal. Requires permits for some areas.",
        "culture" => "Sangai Festival (Nov), Lai Haraoba (Apr/May). Known for classical dance and unique traditions."
    ],
     "Meghalaya" => [
        "best_time" => "October to June (Avoid peak monsoon Jul-Sep)",
        "description" => "Known as 'Abode of Clouds'. Best for waterfalls (Cherrapunji, Mawsynram), living root bridges, caves. Pleasant weather most times except heavy rain.",
        "culture" => "Wangala festival (Nov), Shad Suk Mynsiem (Apr). Matrilineal societies (Khasi, Garo)."
    ],
     "Mizoram" => [
        "best_time" => "October to March (Winter)",
        "description" => "Pleasant weather for exploring rolling hills, lakes, and Aizawl city. Requires permits.",
        "culture" => "Chapchar Kut (March) is a major festival celebrating spring. Bamboo dance (Cheraw) is famous."
    ],
     "Nagaland" => [
        "best_time" => "October to May (Avoid monsoon Jun-Sep)",
        "description" => "Ideal for exploring tribal villages, scenic beauty. Requires permits.",
        "culture" => "Hornbill Festival (Dec 1-10) is a major attraction showcasing diverse Naga tribes and culture."
    ],
    "Odisha" => [
        "best_time" => "October to March (Winter)",
        "description" => "Pleasant weather for visiting temples (Konark, Puri, Bhubaneswar), beaches, and Chilika Lake.",
        "culture" => "Rath Yatra in Puri (Jun/Jul), Konark Dance Festival (Dec). Rich temple architecture and classical dance (Odissi)."
    ],
    "Sikkim" => [
        "best_time" => "March to June (Spring/Summer), October to December (Autumn)",
        "description" => "Spring offers blooming rhododendrons. Autumn has clear mountain views (Kanchenjunga). Ideal for Gangtok, monasteries, trekking.",
        "culture" => "Losar (Feb/Mar), Saga Dawa (May/Jun). Beautiful monasteries and Buddhist culture. Avoid heavy monsoon."
    ],
    "Tripura" => [
        "best_time" => "October to March (Winter)",
        "description" => "Pleasant weather for visiting palaces (Ujjayanta), temples, and exploring Agartala.",
        "culture" => "Garia Puja (Apr), Kharchi Puja (Jul). Mix of Bengali and tribal cultures."
    ],
    "West Bengal" => [
        "best_time" => "October to March (Autumn/Winter)",
        "description" => "Pleasant weather, ideal for sightseeing in Kolkata, exploring Darjeeling's hills, and visiting the Sundarbans.",
        "culture" => "Major festivals like Durga Puja (Oct) and Kali Puja occur. Enjoy vibrant cultural events and comfortable travel conditions."
    ],
    // Central India
    "Chhattisgarh" => [
        "best_time" => "October to March (Winter)",
        "description" => "Pleasant weather for exploring waterfalls (Chitrakote), caves, national parks, and tribal areas.",
        "culture" => "Rich tribal heritage, Bastar Dussehra (Sep/Oct) is unique. Known for forests and indigenous crafts."
    ],
    "Madhya Pradesh" => [
        "best_time" => "October to March (Winter)",
        "description" => "Ideal weather for wildlife safaris (Kanha, Bandhavgarh), historical sites (Khajuraho, Sanchi, Mandu), and cities.",
        "culture" => "Khajuraho Dance Festival (Feb). Known as the 'Heart of India' with diverse attractions."
    ],
    // West India
    "Goa" => [
        "best_time" => "November to February (Winter)",
        "description" => "Peak season with pleasant weather, ideal for beaches, nightlife, water sports.",
        "culture" => "Christmas and New Year celebrations are vibrant. Carnival (Feb/Mar). Monsoon (Jun-Sep) is off-season but lush green."
    ],
    "Gujarat" => [
        "best_time" => "October to March (Winter)",
        "description" => "Pleasant weather for visiting Rann of Kutch, Gir Forest (Asiatic Lions), Ahmedabad, Somnath, Dwarka.",
        "culture" => "Navratri (Sep/Oct) is celebrated grandly. Rann Utsav (Dec-Feb). Kite Festival (Jan)."
    ],
    "Maharashtra" => [
        "best_time" => "October to March (Winter)",
        "description" => "Pleasant weather for exploring Mumbai, Pune, Ajanta & Ellora caves, hill stations (Mahabaleshwar), and beaches.",
        "culture" => "Ganesh Chaturthi (Aug/Sep) and Diwali (Oct/Nov) are major festivals. Rich Maratha history and forts."
    ],
    // South India
    "Andhra Pradesh" => [
        "best_time" => "October to March (Winter)",
        "description" => "Comfortable weather for visiting Tirupati temple, beaches (Vizag), and historical sites.",
        "culture" => "Major festivals like Sankranti (Jan). Rich classical music (Carnatic) and dance (Kuchipudi) traditions."
    ],
    "Karnataka" => [
        "best_time" => "September to February (Post-Monsoon/Winter)",
        "description" => "Pleasant weather for Bangalore, Mysore, Hampi ruins, Coorg hills, and coastal areas.",
        "culture" => "Mysore Dasara (Sep/Oct) is spectacular. Hampi Utsav (Jan/Feb). Rich history and diverse landscapes."
    ],
    "Kerala" => [
        "best_time" => "September to March (Winter/Post-Monsoon)",
        "description" => "Pleasant weather, lush greenery after monsoons. Ideal for backwaters, beaches (Kovalam, Varkala), and hill stations (Munnar).",
        "culture" => "Onam (Aug/Sep) celebrations might extend. Theyyam performances begin. Peak season for Ayurveda treatments."
    ],
     "Tamil Nadu" => [
        "best_time" => "November to February (Winter)",
        "description" => "Avoids extreme heat and monsoon rains. Ideal for temple towns (Madurai, Thanjavur), beaches (Mahabalipuram), hill stations (Ooty).",
        "culture" => "Pongal harvest festival (Jan) is significant. Music and dance festival in Chennai (Dec-Jan). Rich Dravidian culture and architecture."
    ],
    "Telangana" => [
        "best_time" => "October to March (Winter)",
        "description" => "Pleasant weather for exploring Hyderabad (Charminar, Golconda Fort) and other historical sites.",
        "culture" => "Bathukamma festival (Sep/Oct), Bonalu (Jul/Aug). Rich Nizam heritage and cuisine."
    ],
    // Union Territories
    "Andaman and Nicobar Islands" => [
        "best_time" => "October to May (Avoid monsoon Jun-Sep)",
        "description" => "Ideal weather for beaches (Havelock, Neil), water sports (snorkeling, diving), and Cellular Jail visit.",
        "culture" => "Mix of indigenous tribes and settlers. Island Tourism Festival (Jan)."
    ],
     "Puducherry" => [
        "best_time" => "October to March (Winter)",
        "description" => "Pleasant weather, less humidity. Ideal for exploring French Quarter, Auroville, beaches.",
        "culture" => "Unique Franco-Tamil culture. Sri Aurobindo Ashram attracts many visitors."
    ],
];

// Prepare INSERT statement
$sql_insert = "INSERT INTO state_visits (state_name, best_time, description, culture) VALUES (?, ?, ?, ?)";

if ($stmt = mysqli_prepare($conn, $sql_insert)) {
    mysqli_stmt_bind_param($stmt, "ssss", $state_name, $best_time, $description, $culture);

    $count = 0;
    $errors = 0;
    // Loop through data and execute statement
    foreach ($state_visit_info as $name => $info) {
        $state_name = $name;
        $best_time = $info['best_time'];
        $description = $info['description'];
        $culture = $info['culture'];

        if (mysqli_stmt_execute($stmt)) {
            echo "Inserted: " . htmlspecialchars($state_name) . "<br>";
            $count++;
        } else {
            // Check for duplicate entry error (error code 1062) - Should not happen now after fresh table create
            if (mysqli_errno($conn) == 1062) {
                 echo "<span style='color:orange;'>Skipped duplicate (shouldn't happen now): " . htmlspecialchars($state_name) . "</span><br>";
            } else {
                echo "<strong style='color:red;'>Error inserting " . htmlspecialchars($state_name) . ": " . mysqli_stmt_error($stmt) . "</strong><br>";
                $errors++;
            }
        }
    }

    echo "<hr><strong>Insertion complete.</strong><br>";
    echo "Successfully inserted $count states.<br>";
    if ($errors > 0) {
        echo "Encountered $errors insertion errors.<br>";
    } else {
         echo "No insertion errors encountered.<br>";
    }

    mysqli_stmt_close($stmt);

} else {
    echo "<strong style='color:red;'>Error preparing INSERT statement: " . mysqli_error($conn) . "</strong><br>";
}

mysqli_close($conn);

echo "</pre>";

?>
<hr>
<p style="color:red; font-weight:bold;">IMPORTANT: Delete this file (populate_states.php) after running it once successfully!</p>
<p><a href="best_time_to_visit.php">Go back to Visit Planner</a></p> 