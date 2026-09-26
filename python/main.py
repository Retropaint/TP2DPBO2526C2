
class Film:
    def __init__(self, judul="", genre="", durasi=0):
        self.judul = judul
        self.genre = genre
        self.durasi = durasi


class FilmBioskop(Film):
    def __init__(
        self,
        film=None,
        hargaTiket=0,
        namaBioskop="",
        ageRequirement=0
    ):
        if film is not None:
            super().__init__(film.judul, film.genre, film.durasi)

        self.hargaTiket = hargaTiket
        self.namaBioskop = namaBioskop
        self.ageRequirement = ageRequirement


class FilmPremium(FilmBioskop):
    def __init__(
        self,
        film=None,
        levelVip=0,
        jenisBioskop="",
        hargaTiketExtra=0
    ):
        if film is not None:
            super().__init__(
                film,
                film.hargaTiket,
                film.namaBioskop,
                film.ageRequirement
            )

        self.levelVip = levelVip
        self.jenisBioskop = jenisBioskop
        self.hargaTiketExtra = hargaTiketExtra


def printArguments():
    print("Available commands:")
    print(
        "add    [title] [genre] [duration (minutes)] [hargaTiket] "
        "[namaBioskop] [ageRequirement] [levelVip] [jenisBioskop] "
        "[hargaTiketExtra]"
    )
    print("info - shows all films")
    print("help - shows this list")
    print("exit - closes this program")
    print()


def findFilm(films, judul):
    for i, film in enumerate(films):
        if film.judul == judul:
            return i

    return -1


def printFilm(film):
    print(f"{film.judul} {film.genre} {film.durasi}")


def printSeparator(widths):
    for width in widths:
        print("+" + "-" * (width + 2), end="")

    print("+")


def printTable(films):
    atts = [
        "judul",
        "genre",
        "durasi",
        "harga tiket",
        "bioskop",
        "age req",
        "vip level",
        "jenis",
        "extra"
    ]

    # Initial widths based on header lengths
    widths = [len(att) for att in atts]

    # Find the longest value in each column
    for film in films:
        values = [
            film.judul,
            film.genre,
            str(film.durasi),
            str(film.hargaTiket),
            film.namaBioskop,
            str(film.ageRequirement),
            str(film.levelVip),
            film.jenisBioskop,
            str(film.hargaTiketExtra)
        ]

        for i, value in enumerate(values):
            widths[i] = max(widths[i], len(value))

    printSeparator(widths)

    # Headers
    print("|", end=" ")

    for i, att in enumerate(atts):
        print(att + " " * (widths[i] - len(att)), end=" | ")

    print()

    printSeparator(widths)

    # Rows
    for film in films:
        values = [
            film.judul,
            film.genre,
            str(film.durasi),
            str(film.hargaTiket),
            film.namaBioskop,
            str(film.ageRequirement),
            str(film.levelVip),
            film.jenisBioskop,
            str(film.hargaTiketExtra)
        ]

        print("|", end=" ")

        for i, value in enumerate(values):
            print(value + " " * (widths[i] - len(value)), end=" | ")

        print()

    printSeparator(widths)


def main():
    printArguments()

    films = []

    while True:
        action = input("")

        if action == "add":
            film = FilmPremium()

            # Read the title first, just like the C++ version
            film.judul = input()

            if findFilm(films, film.judul) != -1:
                print("This film already exists!")
            else:
                film.genre = input()
                film.durasi = int(input())
                film.hargaTiket = int(input())
                film.namaBioskop = input()
                film.ageRequirement = int(input())
                film.levelVip = int(input())
                film.jenisBioskop = input()
                film.hargaTiketExtra = int(input())

                films.append(film)

                print(f"Added {film.judul}!")

        elif action == "show":
            if len(films) > 0:
                printTable(films)
            else:
                print("No films!")

        elif action == "exit":
            print("bye!")
            break

        elif action == "help":
            printArguments()

        elif action != "":
            print(f"{action} unrecognized.")


if __name__ == "__main__":
    main()
