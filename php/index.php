<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>TP1 Film Bioskop</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h1>TP1</h1>

<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ERROR);

session_start();

/*
 * Same inheritance hierarchy as the C++ version:
 *
 * Film
 *   └── FilmBioskop
 *         └── FilmPremium
 */

class Film
{
    public string $judul;
    public string $genre;
    public int $durasi;

    public function __construct(
        string $judul = "",
        string $genre = "",
        int $durasi = 0
    ) {
        $this->judul = $judul;
        $this->genre = $genre;
        $this->durasi = $durasi;
    }
}

class FilmBioskop extends Film
{
    public int $hargaTiket;
    public string $namaBioskop;
    public int $ageRequirement;

    public function __construct(
        string $judul = "",
        string $genre = "",
        int $durasi = 0,
        int $hargaTiket = 0,
        string $namaBioskop = "",
        int $ageRequirement = 0
    ) {
        parent::__construct($judul, $genre, $durasi);

        $this->hargaTiket = $hargaTiket;
        $this->namaBioskop = $namaBioskop;
        $this->ageRequirement = $ageRequirement;
    }
}

class FilmPremium extends FilmBioskop
{
    public int $levelVip;
    public string $jenisBioskop;
    public int $hargaTiketExtra;

    public function __construct(
        string $judul = "",
        string $genre = "",
        int $durasi = 0,
        int $hargaTiket = 0,
        string $namaBioskop = "",
        int $ageRequirement = 0,
        int $levelVip = 0,
        string $jenisBioskop = "",
        int $hargaTiketExtra = 0
    ) {
        parent::__construct(
            $judul,
            $genre,
            $durasi,
            $hargaTiket,
            $namaBioskop,
            $ageRequirement
        );

        $this->levelVip = $levelVip;
        $this->jenisBioskop = $jenisBioskop;
        $this->hargaTiketExtra = $hargaTiketExtra;
    }
}

function loadFilms(): array
{
    $path = "./data.json";

    if (!file_exists($path) || filesize($path) === 0) {
        return [];
    }

    $data = json_decode(file_get_contents($path), true);

    if (!is_array($data)) {
        return [];
    }

    $films = [];

    foreach ($data as $film) {
        if (!is_array($film)) {
            continue;
        }

        // Every element is a FilmPremium, just like vector<FilmPremium> in C++.
        $films[] = new FilmPremium(
            (string)($film["judul"] ?? ""),
            (string)($film["genre"] ?? ""),
            (int)($film["durasi"] ?? 0),
            (int)($film["hargaTiket"] ?? 0),
            (string)($film["namaBioskop"] ?? ""),
            (int)($film["ageRequirement"] ?? 0),
            (int)($film["levelVip"] ?? 0),
            (string)($film["jenisBioskop"] ?? ""),
            (int)($film["hargaTiketExtra"] ?? 0)
        );
    }

    return $films;
}

function saveFilms(array $films): void
{
    file_put_contents(
        "./data.json",
        json_encode($films, JSON_PRETTY_PRINT)
    );
}

function findFilm(array $films, string $judul): int
{
    for ($i = 0; $i < count($films); $i++) {
        if ($films[$i]->judul === $judul) {
            return $i;
        }
    }

    return -1;
}

/*
 * HTML version of the C++ printTable().
 * The <th> and <td> columns correspond to the same nine attributes:
 *
 * judul, genre, durasi, harga tiket, bioskop,
 * age req, vip level, jenis, extra
 */
function printTable(array $films): void
{
    if (count($films) === 0) {
        echo "<p>No films!</p>";
        return;
    }

    echo '<table border="1" cellspacing="0" cellpadding="6">';
    echo "<thead>";
    echo "<tr>";
    echo "<th>judul</th>";
    echo "<th>genre</th>";
    echo "<th>durasi</th>";
    echo "<th>harga tiket</th>";
    echo "<th>bioskop</th>";
    echo "<th>age req</th>";
    echo "<th>vip level</th>";
    echo "<th>jenis</th>";
    echo "<th>extra</th>";
    echo "<th>action</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach ($films as $i => $film) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($film->judul) . "</td>";
        echo "<td>" . htmlspecialchars($film->genre) . "</td>";
        echo "<td>" . $film->durasi . "</td>";
        echo "<td>" . $film->hargaTiket . "</td>";
        echo "<td>" . htmlspecialchars($film->namaBioskop) . "</td>";
        echo "<td>" . $film->ageRequirement . "</td>";
        echo "<td>" . $film->levelVip . "</td>";
        echo "<td>" . htmlspecialchars($film->jenisBioskop) . "</td>";
        echo "<td>" . $film->hargaTiketExtra . "</td>";
        echo '<td>
                <form method="POST" style="margin:0">
                    <input type="hidden" name="actionType" value="del">
                    <input type="hidden" name="idx" value="' . $i . '">
                    <button type="submit">Del</button>
                </form>
              </td>';
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
}

$films = loadFilms();
$shownFilms = $films;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["actionType"] ?? "";

    if ($action === "add") {
        $judul = trim((string)($_POST["judul"] ?? ""));

        if (findFilm($films, $judul) !== -1) {
            echo "<p>This film already exists!</p>";
        } else {
            $newFilm = new FilmPremium(
                $judul,
                (string)($_POST["genre"] ?? ""),
                (int)($_POST["durasi"] ?? 0),
                (int)($_POST["hargaTiket"] ?? 0),
                (string)($_POST["namaBioskop"] ?? ""),
                (int)($_POST["ageRequirement"] ?? 0),
                (int)($_POST["levelVip"] ?? 0),
                (string)($_POST["jenisBioskop"] ?? ""),
                (int)($_POST["hargaTiketExtra"] ?? 0)
            );

            $films[] = $newFilm;
            saveFilms($films);

            echo "<p>Added " . htmlspecialchars($newFilm->judul) . "!</p>";
        }
    } elseif ($action === "del") {
        $idx = (int)($_POST["idx"] ?? -1);

        if (isset($films[$idx])) {
            unset($films[$idx]);
            $films = array_values($films);
            saveFilms($films);
        }
    } elseif ($action === "find") {
        $judul = (string)($_POST["judul"] ?? "");

        $shownFilms = array_values(array_filter(
            $films,
            function (FilmPremium $film) use ($judul): bool {
                return $film->judul === $judul || $judul === "";
            }
        ));
    }
}
?>

<h2>Add Film</h2>

<form method="POST">
    <input type="hidden" name="actionType" value="add">

    Title:
    <input type="text" name="judul" required>
    <br>

    Genre:
    <input type="text" name="genre" required>
    <br>

    Duration (minutes):
    <input type="number" name="durasi" required>
    <br>

    Price/ticket:
    <input type="number" name="hargaTiket" required>
    <br>

    Cinema:
    <input type="text" name="namaBioskop" required>
    <br>

    Age requirement:
    <input type="number" name="ageRequirement" required>
    <br>

    VIP level:
    <input type="number" name="levelVip" required>
    <br>

    Cinema type:
    <input type="text" name="jenisBioskop" required>
    <br>

    Extra ticket price:
    <input type="number" name="hargaTiketExtra" required>
    <br><br>

    <button type="submit">Add Film</button>
</form>

<h2>Find Film</h2>

<form method="POST">
    <input type="hidden" name="actionType" value="find">

    Find:
    <input type="text" name="judul">

    <button type="submit">Find Film</button>
</form>

<h2>Films</h2>

<?php
printTable($shownFilms);
?>

</body>
</html>
