
import java.io.File;
import java.io.FileNotFoundException;
import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;

class Film {
  public String judul;
  public String genre;
  public int durasi;

  public Film() {
  }

  public Film(String judul, String genre, int durasi) {
    this.judul = judul;
    this.genre = genre;
    this.durasi = durasi;
  }
}

class FilmBioskop extends Film {
  public int hargaTiket;
  public String namaBioskop;
  public int ageRequirement;

  public FilmBioskop() {
  }

  public FilmBioskop(Film film, int hargaTiket, String namaBioskop,
      int ageRequirement) {
    this.judul = film.judul;
    this.genre = film.genre;
    this.durasi = film.durasi;

    this.hargaTiket = hargaTiket;
    this.namaBioskop = namaBioskop;
    this.ageRequirement = ageRequirement;
  }
}

class FilmPremium extends FilmBioskop {
  public int levelVip;
  public String jenisBioskop;
  public int hargaTiketExtra;

  public FilmPremium() {
  }

  public FilmPremium(FilmBioskop film, int levelVip, String jenisBioskop,
      int hargaTiketExtra) {
    this.judul = film.judul;
    this.genre = film.genre;
    this.durasi = film.durasi;

    this.hargaTiket = film.hargaTiket;
    this.namaBioskop = film.namaBioskop;
    this.ageRequirement = film.ageRequirement;

    this.levelVip = levelVip;
    this.jenisBioskop = jenisBioskop;
    this.hargaTiketExtra = hargaTiketExtra;
  }
}

public class Main {

  static void printArguments() {
    System.out.println("Available commands:");
    System.out.println(
        "add    [title] [genre] [duration (minutes)] [hargaTiket] " +
            "[namaBioskop] [ageRequirement] [levelVip] [jenisBioskop] " +
            "[hargaTiketExtra]");
    System.out.println("info - shows all films");
    System.out.println("help - shows this list");
    System.out.println("exit - closes this program");
    System.out.println();
  }

  static int findFilm(List<FilmPremium> films, String judul) {
    for (int f = 0; f < films.size(); f++) {
      if (films.get(f).judul.equals(judul)) {
        return f;
      }
    }

    return -1;
  }

  static void printFilm(Film film) {
    System.out.println(
        film.judul + " " +
            film.genre + " " +
            film.durasi);
  }

  static void printSeparator(int[] widths) {
    for (int width : widths) {
      System.out.print("+" + "-".repeat(width + 2));
    }
    System.out.println("+");
  }

  static String ps(int width) {
    return " ".repeat(width);
  }

  static void printTable(List<FilmPremium> films) {
    String[] atts = {
        "judul",
        "genre",
        "durasi",
        "harga tiket",
        "bioskop",
        "age req",
        "vip level",
        "jenis",
        "extra"
    };

    int[] widths = new int[9];

    for (int i = 0; i < atts.length; i++) {
      widths[i] = atts[i].length();
    }

    // Find the longest value for each column
    for (FilmPremium film : films) {
      widths[0] = Math.max(widths[0], film.judul.length());
      widths[1] = Math.max(widths[1], film.genre.length());
      widths[2] = Math.max(widths[2],
          String.valueOf(film.durasi).length());
      widths[3] = Math.max(widths[3],
          String.valueOf(film.hargaTiket).length());
      widths[4] = Math.max(widths[4], film.namaBioskop.length());
      widths[5] = Math.max(widths[5],
          String.valueOf(film.ageRequirement).length());
      widths[6] = Math.max(widths[6],
          String.valueOf(film.levelVip).length());
      widths[7] = Math.max(widths[7], film.jenisBioskop.length());
      widths[8] = Math.max(widths[8],
          String.valueOf(film.hargaTiketExtra).length());
    }

    printSeparator(widths);

    // Print headers
    System.out.print("| ");
    for (int i = 0; i < 9; i++) {
      System.out.print(
          atts[i] +
              ps(widths[i] - atts[i].length()) +
              " | ");
    }
    System.out.println();

    printSeparator(widths);

    // Print rows
    for (FilmPremium f : films) {
      int strDurasi = String.valueOf(f.durasi).length();
      int strTiket = String.valueOf(f.hargaTiket).length();
      int strAge = String.valueOf(f.ageRequirement).length();
      int strVip = String.valueOf(f.levelVip).length();
      int strExtra = String.valueOf(f.hargaTiketExtra).length();

      System.out.print(
          "| " + f.judul +
              ps(widths[0] - f.judul.length()));

      System.out.print(
          " | " + f.genre +
              ps(widths[1] - f.genre.length()));

      System.out.print(
          " | " + f.durasi +
              ps(widths[2] - strDurasi));

      System.out.print(
          " | " + f.hargaTiket +
              ps(widths[3] - strTiket));

      System.out.print(
          " | " + f.namaBioskop +
              ps(widths[4] - f.namaBioskop.length()));

      System.out.print(
          " | " + f.ageRequirement +
              ps(widths[5] - strAge));

      System.out.print(
          " | " + f.levelVip +
              ps(widths[6] - strVip));

      System.out.print(
          " | " + f.jenisBioskop +
              ps(widths[7] - f.jenisBioskop.length()));

      System.out.print(
          " | " + f.hargaTiketExtra +
              ps(widths[8] - strExtra) +
              " |");

      System.out.println();
    }

    printSeparator(widths);
  }

  public static boolean takeInput(Scanner scanner, List<FilmPremium> films) {
    System.out.println("action:");
    String action = scanner.next();

    if (action.equals("add")) {
      FilmPremium film = new FilmPremium();
      film.judul = scanner.next();
      if (findFilm(films, film.judul) != -1) {
        System.out.println("This film already exists!");
      } else {
        film.genre = scanner.next();
        film.durasi = scanner.nextInt();
        film.hargaTiket = scanner.nextInt();
        film.namaBioskop = scanner.next();
        film.ageRequirement = scanner.nextInt();
        film.levelVip = scanner.nextInt();
        film.jenisBioskop = scanner.next();
        film.hargaTiketExtra = scanner.nextInt();

        films.add(film);

        System.out.println("Added " + film.judul + "!");
      }
    } else if (action.equals("show")) {
      if (!films.isEmpty()) {
        printTable(films);
      } else {
        System.out.println("No films!");
      }
    } else if (action.equals("exit")) {
      System.out.println("bye!");
      return true;
    } else if (action.equals("help")) {
      printArguments();
    } else if (!action.isEmpty()) {
      System.out.println(action + " unrecognized.");
    }

    return false;
  }

  public static void main(String[] args) {
    printArguments();

    List<FilmPremium> films = new ArrayList<>();
    File file = new File("./input.txt");

    try (Scanner scanner = new Scanner(file)) {
      while (scanner.hasNext()) {
        if (takeInput(scanner, films)) {
          break;
        }
      }
    } catch (FileNotFoundException e) {
    }

    Scanner scanner = new Scanner(System.in);

    while (true) {
      if (takeInput(scanner, films)) {
        break;
      }
    }

    scanner.close();
  }
}
