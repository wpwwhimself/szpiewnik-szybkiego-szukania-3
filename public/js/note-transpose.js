// note transposer lifted from http://www.franziskaludwig.de/abctransposer/index.php

function Hoch(noteInput, e = undefined) {
    e?.preventDefault();
    const fieldMode = noteInput instanceof HTMLElement;
    verarbeiten = fieldMode ? noteInput.value : noteInput;
    neu = escape(verarbeiten);

    Reihe = neu.split("%0D%0A");
    Reihe = neu.split("%0A");

    let key = "";

    for (i = 0; i < Reihe.length; ++i) {
        Reihe[i] = unescape(Reihe[i]); /* Macht die Steuerzeichen wieder weg */
        Aktuellereihe = Reihe[i].split(""); /* nochmal bei C. Walshaw crosschecken, ob alle mögl. ausser K: erfasst. */
        if ((Aktuellereihe[0] == "w" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "A" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "B" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "C" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "D" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "E" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "F" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "G" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "H" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "I" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "J" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "L" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "M" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "N" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "O" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "P" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "Q" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "R" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "S" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "T" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "U" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "V" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "W" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "X" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "Y" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "Z" && Aktuellereihe[1] == ":")) {
            /* Alle ausser K: und Melodieteile werden hier ignoriert. */
        }
        else if (Aktuellereihe[0] == "K" && Aktuellereihe[1] == ":") /* k: Feld wird hier behandelt */ {
            Leerzeichenweg = Reihe[i].split(" "); /* weil manchmal Leerzeichen nachm k */
            sindweg = Leerzeichenweg.join("");
            Aktuellereihe = sindweg.split(""); /* den dritten ersetzen durch aktuellen Ton */
            if (Aktuellereihe[2] == "C") {
                Aktuellereihe[2] = "D";
                Reihe[i] = Aktuellereihe.join("");
            }
            else if (Aktuellereihe[2] == "D") {
                Aktuellereihe[2] = "E";
                Reihe[i] = Aktuellereihe.join("");
            }
            else if (Aktuellereihe[2] == "E") {
                Aktuellereihe[2] = "F";
                Reihe[i] = Aktuellereihe.join("");
            }
            else if (Aktuellereihe[2] == "F") {
                Aktuellereihe[2] = "G";
                Reihe[i] = Aktuellereihe.join("");
            }
            else if (Aktuellereihe[2] == "G") {
                Aktuellereihe[2] = "A";
                Reihe[i] = Aktuellereihe.join("");
            }
            else if (Aktuellereihe[2] == "A") {
                Aktuellereihe[2] = "B";
                Reihe[i] = Aktuellereihe.join("");
            }
            else if (Aktuellereihe[2] == "B") {
                Aktuellereihe[2] = "C";
                Reihe[i] = Aktuellereihe.join("");
            }
            else {
                /* nur für den Fall, falls korrupt */
            }

            key = Aktuellereihe.join("").substring(2);
        }
        else /* hier die Melodieabschnitte bearbeiten */ {
            Derarray = Reihe[i].split("");
            for (x = 0; x < Derarray.length; ++x) /* zum Erstellen von a'' oder A,, -Klumpen */ {
                allefertig = false;
                mitzaehl = 0;
                if ((Derarray[x + 1] == "'") || (Derarray[x + 1] == ",")) {
                    do {
                        mitzaehl = mitzaehl + 1;
                        if (Derarray[x + mitzaehl] == "'") {
                            Derarray[x] = Derarray[x] + "'";
                            Derarray[x + mitzaehl] = ""; /* Arrays mit ' löschen */
                        }
                        else if (Derarray[x + mitzaehl] == ",") {
                            Derarray[x] = Derarray[x] + ",";
                            Derarray[x + mitzaehl] = ""; /* Arrays mit ' löschen */
                        }
                        else {
                            allefertig = true; /* wenn alle ' in dem Abschnitt fertig sind - Ende. */
                        }
                    }
                    while (allefertig == false);
                }
                else {
                    /* wenn es kein Klumpen ist, hier erstmal nix verändern */
                }
            }
            for (y = 0; y < Derarray.length; ++y) /* Tonhöhe ändern */ {
                Miniarray = Derarray[y].split("");
                if (Miniarray[0] == "B" && Miniarray[1] == ",") /* Ausnahmefall 1 (, löschen) */ {
                    Miniarray[0] = "C";
                    Miniarray[1] = "";
                }
                else if (Miniarray[0] == "b" && Miniarray[1] == "'") /* Ausnahmefall 2 (' hinzufügen) */ {
                    Miniarray[0] = "c";
                    Miniarray[1] = "''";
                }
                else if (Miniarray[0] == "C") {
                    Miniarray[0] = "D";
                }
                else if (Miniarray[0] == "D") {
                    Miniarray[0] = "E";
                }
                else if (Miniarray[0] == "E") {
                    Miniarray[0] = "F";
                }
                else if (Miniarray[0] == "F") {
                    Miniarray[0] = "G";
                }
                else if (Miniarray[0] == "G") {
                    Miniarray[0] = "A";
                }
                else if (Miniarray[0] == "A") {
                    Miniarray[0] = "B";
                }
                else if (Miniarray[0] == "B") {
                    Miniarray[0] = "c";
                }
                else if (Miniarray[0] == "c") {
                    Miniarray[0] = "d";
                }
                else if (Miniarray[0] == "d") {
                    Miniarray[0] = "e";
                }
                else if (Miniarray[0] == "e") {
                    Miniarray[0] = "f";
                }
                else if (Miniarray[0] == "f") {
                    Miniarray[0] = "g";
                }
                else if (Miniarray[0] == "g") {
                    Miniarray[0] = "a";
                }
                else if (Miniarray[0] == "a") {
                    Miniarray[0] = "b";
                }
                else if (Miniarray[0] == "b") {
                    Miniarray[0] = "c'";
                }
                Derarray[y] = Miniarray.join("");
            }




            alleszusammen = Derarray.join("");
            Haukommaingriffweg = alleszusammen.split("\"");
            for (m = 0; m < Haukommaingriffweg.length; ++m) /* Sonderzeichen NUR innerhalb von " und " wegmachen - also jeden 2. wenn array durchgegangen wird. */ {
                if (m % 2 == 0)  // wenn Zahl gerade ist nichts machen - die ungeraden sollten innerhalb der anführungszeichen sein.
                {

                }
                else {
                    Haukommaingriffweg[m] = Haukommaingriffweg[m].replace(/'/g, "");
                    Haukommaingriffweg[m] = Haukommaingriffweg[m].replace(/,/g, "");
                    // Doof = Haukommaingriffweg[m].split(""); // Damit Gitarrengriffe immer groß anfangen
                    // Doof[0] = Doof[0].toUpperCase();
                    // Haukommaingriffweg[m] = Doof.join("");

                    // fix non-transposable symbols
                    Haukommaingriffweg[m] = Haukommaingriffweg[m].replace(/mbj/g, "maj");
                    // fix chords
                    const transposedByOneStep = ["C", "F"].includes(key.toUpperCase())
                    Haukommaingriffweg[m] = Haukommaingriffweg[m]
                        .replace(/h/g, transposedByOneStep ? "c" : "cis")
                        .replace(/H/g, transposedByOneStep ? "C" : "Cis")
                }
            }
            alleszusammen = Haukommaingriffweg.join("\"");
            Reihe[i] = alleszusammen;


        }


    }

    insfeld = Reihe.join("\n");

    if (fieldMode) {
        noteInput.value = insfeld;
        Notenzeigen(noteInput);
    } else {
        noteInput = insfeld;
        return noteInput;
    }
}

function Notenzeigen(noteInput){
    const event = new Event("keyup");
    noteInput.dispatchEvent(event);
}

function Runter(noteInput, e = undefined) {
    e?.preventDefault();
    const fieldMode = noteInput instanceof HTMLElement;
    verarbeiten = fieldMode ? noteInput.value : noteInput;

    neu = escape(verarbeiten);

    Reihe = neu.split("%0D%0A");
    Reihe = neu.split("%0A");

    let key = "";

    for (i = 0; i < Reihe.length; ++i) {
        Reihe[i] = unescape(Reihe[i]); /* Macht die Steuerzeichen wieder weg */

        Aktuellereihe = Reihe[i].split(""); /* nochmal bei C. Walshaw crosschecken, ob alle mögl. ausser K: erfasst. */
        if ((Aktuellereihe[0] == "w" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "A" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "B" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "C" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "D" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "E" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "F" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "G" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "H" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "I" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "J" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "L" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "M" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "N" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "O" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "P" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "Q" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "R" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "S" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "T" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "U" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "V" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "W" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "X" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "Y" && Aktuellereihe[1] == ":") || (Aktuellereihe[0] == "Z" && Aktuellereihe[1] == ":")) {
            /* Alle ausser K: und Melodieteile werden hier ignoriert. */
        }
        else if (Aktuellereihe[0] == "K" && Aktuellereihe[1] == ":") /* k: Feld wird hier behandelt */ {
            Leerzeichenweg = Reihe[i].split(" "); /* weil manchmal Leerzeichen nachm k */
            sindweg = Leerzeichenweg.join("");
            Aktuellereihe = sindweg.split(""); /* den dritten ersetzen durch aktuellen Ton */
            if (Aktuellereihe[2] == "C") {
                Aktuellereihe[2] = "B";
                Reihe[i] = Aktuellereihe.join("");
            }
            else if (Aktuellereihe[2] == "D") {
                Aktuellereihe[2] = "C";
                Reihe[i] = Aktuellereihe.join("");
            }
            else if (Aktuellereihe[2] == "E") {
                Aktuellereihe[2] = "D";
                Reihe[i] = Aktuellereihe.join("");
            }
            else if (Aktuellereihe[2] == "F") {
                Aktuellereihe[2] = "E";
                Reihe[i] = Aktuellereihe.join("");
            }
            else if (Aktuellereihe[2] == "G") {
                Aktuellereihe[2] = "F";
                Reihe[i] = Aktuellereihe.join("");
            }
            else if (Aktuellereihe[2] == "A") {
                Aktuellereihe[2] = "G";
                Reihe[i] = Aktuellereihe.join("");
            }
            else if (Aktuellereihe[2] == "B") {
                Aktuellereihe[2] = "A";
                Reihe[i] = Aktuellereihe.join("");
            }
            else {
                /* nur für den Fall, falls korrupt */
            }

            key = Aktuellereihe.join("").substring(2);
        }
        else /* hier die Melodieabschnitte bearbeiten */ {
            Derarray = Reihe[i].split("");
            for (x = 0; x < Derarray.length; ++x) /* zum Erstellen von a'' oder A,, -Klumpen */ {
                allefertig = false;
                mitzaehl = 0;
                if ((Derarray[x + 1] == "'") || (Derarray[x + 1] == ",")) {
                    do {
                        mitzaehl = mitzaehl + 1;
                        if (Derarray[x + mitzaehl] == "'") {
                            Derarray[x] = Derarray[x] + "'";
                            Derarray[x + mitzaehl] = ""; /* Arrays mit ' löschen */
                        }
                        else if (Derarray[x + mitzaehl] == ",") {
                            Derarray[x] = Derarray[x] + ",";
                            Derarray[x + mitzaehl] = ""; /* Arrays mit ' löschen */
                        }
                        else {
                            allefertig = true; /* wenn alle ' in dem Abschnitt fertig sind - Ende. */
                        }
                    }
                    while (allefertig == false);
                }
                else {
                    /* wenn es kein Klumpen ist, hier erstmal nix verändern */
                }
            }
            for (y = 0; y < Derarray.length; ++y) /* Tonhöhe ändern */ {
                Miniarray = Derarray[y].split("");
                if (Miniarray[0] == "C" && Miniarray[1] == ",") /* Ausnahmefall 1 (, hinzufügen) */ {
                    Miniarray[0] = "B";
                    Miniarray[1] = ",,";
                }
                else if (Miniarray[0] == "c" && Miniarray[1] == "'") /* Ausnahmefall 2 (' hinzufügen) */ {
                    Miniarray[0] = "b";
                    Miniarray[1] = "";
                }
                else if (Miniarray[0] == "C") {
                    Miniarray[0] = "B,";
                }
                else if (Miniarray[0] == "D") {
                    Miniarray[0] = "C";
                }
                else if (Miniarray[0] == "E") {
                    Miniarray[0] = "D";
                }
                else if (Miniarray[0] == "F") {
                    Miniarray[0] = "E";
                }
                else if (Miniarray[0] == "G") {
                    Miniarray[0] = "F";
                }
                else if (Miniarray[0] == "A") {
                    Miniarray[0] = "G";
                }
                else if (Miniarray[0] == "B") {
                    Miniarray[0] = "A";
                }
                else if (Miniarray[0] == "c") {
                    Miniarray[0] = "B";
                }
                else if (Miniarray[0] == "d") {
                    Miniarray[0] = "c";
                }
                else if (Miniarray[0] == "e") {
                    Miniarray[0] = "d";
                }
                else if (Miniarray[0] == "f") {
                    Miniarray[0] = "e";
                }
                else if (Miniarray[0] == "g") {
                    Miniarray[0] = "f";
                }
                else if (Miniarray[0] == "a") {
                    Miniarray[0] = "g";
                }
                else if (Miniarray[0] == "b") {
                    Miniarray[0] = "a";
                }
                Derarray[y] = Miniarray.join("");
            }
            alleszusammen = Derarray.join("");
            Haukommaingriffweg = alleszusammen.split("\"");
            for (m = 0; m < Haukommaingriffweg.length; ++m) /* Sonderzeichen NUR innerhalb von " und " wegmachen - also jeden 2. wenn array durchgegangen wird. */ {
                if (m % 2 == 0)  // wenn Zahl gerade ist nichts machen - die ungeraden sollten innerhalb der anführungszeichen sein.
                {

                }
                else {
                    Haukommaingriffweg[m] = Haukommaingriffweg[m].replace(/'/g, "");
                    Haukommaingriffweg[m] = Haukommaingriffweg[m].replace(/,/g, "");
                    // Doof = Haukommaingriffweg[m].split(""); // Damit Gitarrengriffe immer groß anfangen
                    // Doof[0] = Doof[0].toUpperCase();
                    // Haukommaingriffweg[m] = Doof.join("");

                    // fix non-transposable symbols
                    Haukommaingriffweg[m] = Haukommaingriffweg[m].replace(/mgj/g, "maj");
                    // fix chords
                    const transposedByOneStep = ["B", "E"].includes(key.toUpperCase())
                    Haukommaingriffweg[m] = Haukommaingriffweg[m]
                        .replace(/h/g, transposedByOneStep ? "ais" : "a")
                        .replace(/H/g, transposedByOneStep ? "Ais" : "A")
                }
            }
            alleszusammen = Haukommaingriffweg.join("\"");

            Reihe[i] = alleszusammen;



        }


    }

    insfeld = Reihe.join("\n");

    if (fieldMode) {
        noteInput.value = insfeld;
        Notenzeigen(noteInput);
    } else {
        noteInput = insfeld;
        return noteInput;
    }
}
