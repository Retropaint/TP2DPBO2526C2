#include <algorithm>
#include <iomanip>
#include <iostream>
#include <string>
#include <vector>

using namespace std;

class Film {
public:
  string judul;
  string genre;
  int durasi;

  Film() {}

  Film(string judul, string genre, int durasi) {
    this->judul = judul;
    this->genre = genre;
    this->durasi = durasi;
  }
};

class FilmBioskop : public Film {
public:
  int hargaTiket;
  string namaBioskop;
  int ageRequirement;

  FilmBioskop() {}

  FilmBioskop(Film film, float hargaTiket, string namaBioskop,
              int ageRequirement) {
    this->namaBioskop = namaBioskop;
    this->hargaTiket = hargaTiket;
    this->ageRequirement = ageRequirement;
  }
};

class FilmPremium : public FilmBioskop {
public:
  int levelVip;
  string jenisBioskop;
  int hargaTiketExtra;

  FilmPremium() {}

  FilmPremium(FilmPremium filmPremium, int levelVip, string jenisBioskop,
              float hargaTiketExtra) {
    this->levelVip = levelVip;
    this->jenisBioskop = jenisBioskop;
    this->hargaTiketExtra = hargaTiketExtra;
  }
};

void printArguments() {
  cout << "Available commands:\n";
  cout << "add    [title] [genre] [duration (minutes)] [hargaTiket] "
          "[namaBioskop] [ageRequirement] [levelVip] [jenisBioskop] "
          "[hargaTiketExtra]\n";
  cout << "info - shows all films\n";
  cout << "help - shows this list\n";
  cout << "exit - closes this program\n";
  cout << "\n";
}

int findFilm(vector<FilmPremium> films, string judul) {
  for (int f = 0; f < films.size(); f++) {
    if (films[f].judul == judul) {
      return f;
    }
  }
  return -1;
}

void printFilm(Film film) {
  cout << film.judul << " " << film.genre << " " << film.durasi << " "
       << "\n";
}

// resets input buffer, to prevent registering excess inputs
void clearInput() {
  return;
  std::cin.clear();
}

void printSeparator(size_t widths[9]) {
  for (int i = 0; i < 9; i++) {
    cout << "+" << string(widths[i] + 2, '-');
  }
  cout << "+\n";
}

string ps(size_t width) {
  string spaces = "";
  for (int i = 0; i < width; i++) {
    spaces += " ";
  }
  return spaces;
}

void printTable(const vector<FilmPremium> &films) {

  string atts[] = {"judul",   "genre",     "durasi", "harga tiket", "bioskop",
                   "age req", "vip level", "jenis",  "extra"};
  size_t widths[] = {atts[0].length(), atts[1].length(), atts[2].length(),
                     atts[3].length(), atts[4].length(), atts[5].length(),
                     atts[6].length(), atts[7].length(), atts[8].length()};

  // Find the longest value for each column
  for (int i = 0; i < films.size(); i++) {
    widths[0] = max(widths[0], films[i].judul.length());
    widths[1] = max(widths[1], films[i].genre.length());
    widths[2] = max(widths[2], to_string(films[i].durasi).length());
    widths[3] = max(widths[3], to_string(films[i].hargaTiket).length());
    widths[4] = max(widths[4], films[i].namaBioskop.length());
    widths[5] = max(widths[5], to_string(films[i].ageRequirement).length());
    widths[6] = max(widths[6], to_string(films[i].levelVip).length());
    widths[7] = max(widths[7], films[i].jenisBioskop.length());
    widths[8] = max(widths[8], to_string(films[i].hargaTiketExtra).length());
  }

  printSeparator(widths);

  // Print headers
  cout << "| ";
  for (int i = 0; i < 9; i++) {
    cout << atts[i] << ps(widths[i] - atts[i].length()) << " | ";
  }
  cout << endl;

  printSeparator(widths);

  // Print rows
  for (int i = 0; i < films.size(); i++) {
    FilmPremium f = films[i];
    int strDurasi = to_string(f.durasi).length();
    int strTiket = to_string(f.hargaTiket).length();
    int strAge = to_string(f.ageRequirement).length();
    int levelVip = to_string(f.levelVip).length();
    int strExtra = to_string(f.hargaTiketExtra).length();
    cout << "| " << f.judul << ps(widths[0] - f.judul.length()) << flush;
    cout << " | " << f.genre << ps(widths[1] - f.genre.length()) << flush;
    cout << " | " << f.durasi << ps(widths[2] - strDurasi) << flush;
    cout << " | " << f.hargaTiket << ps(widths[3] - strTiket) << flush;
    cout << " | " << f.namaBioskop << ps(widths[4] - f.namaBioskop.length())
         << flush;
    cout << " | " << f.ageRequirement << ps(widths[5] - strAge) << flush;
    cout << " | " << f.levelVip << ps(widths[6] - levelVip) << flush;
    cout << " | " << f.jenisBioskop << ps(widths[7] - f.jenisBioskop.length())
         << flush;
    cout << " | " << f.hargaTiketExtra << ps(widths[8] - strExtra) << " |"
         << flush;
    cout << endl;
  }

  printSeparator(widths);
}

int main() {
  printArguments();
  vector<FilmPremium> films;

  while (true) {
    string action;
    cout << "action: " << endl;
    cin >> action;

    if (action == "add") {
      FilmPremium film = FilmPremium();
      cin >> film.judul;
      if (findFilm(films, film.judul) != -1) {
        cout << "This film already exists!\n";
      } else {
        cin >> film.genre >> film.durasi >> film.hargaTiket >>
            film.namaBioskop >> film.ageRequirement >> film.levelVip >>
            film.jenisBioskop >> film.hargaTiketExtra;
        films.push_back(film);
        cout << "Added " << film.judul << "!\n";
      }
      clearInput();
    } else if (action == "show") {
      if (films.size() > 0) {
        printTable(films);
      } else {
        cout << "No films!\n";
      }
      clearInput();
    } else if (action == "exit") {
      cout << "bye!\n";
      break;
    } else if (action == "help") {
      printArguments();
      clearInput();
    } else if (action != "") {
      cout << action << " unrecognized.\n";
      clearInput();
    }
  }
}
