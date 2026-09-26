# TP2

## Code Architecture

There are 3 classes with the following attributes:

- `Film`
    - `Judul`
    - `Genre`
    - `Durasi (menit)`
- `FilmBioskop`
    - `HargaTiket`
    - `namaBioskop`
    - `ageRequirement`
- `FilmPremium`
    - `levelVip`
    - `jenisBioskop`
    - `hargaTiketExtra`

All programs have the following commands:

- `add`
- `show` (not in PHP, since films are always shown)

All CLIs run on an infinite while-loop that scans for user input. Upon entering
the `exit` command, the while loop is broken and the program is stopped.

## PHP

The PHP form stores data in `data.json`. The `films` array is encoded into the
file as JSON, and decoded when reading the file.

Any actions on the page (adding, deleting, finding, etc) will refresh the page
with a `POST` method. This method contains an `actionType` attribute, similarly
to the above commands (`add`, etc).
