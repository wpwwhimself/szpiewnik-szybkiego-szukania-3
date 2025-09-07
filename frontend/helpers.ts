import { SelectOption } from "./types";

export function slugAndDePL(string: string): string{
  return string
    .replace(/([a-z])([A-Z])/g, "$1-$2")
    .replace(/[Ąą]/g, "a")
    .replace(/[Ćć]/g, "c")
    .replace(/[Ęę]/g, "e")
    .replace(/[Łł]/g, "l")
    .replace(/[Ńń]/g, "n")
    .replace(/[Óó]/g, "o")
    .replace(/[Śś]/g, "s")
    .replace(/[ŹŻźż]/g, "z")
    .toLocaleLowerCase()
    .replace(/ +/g, "-")
    .replace(/-+/g, "-")
    .replace(/[,:\(\)]/g, "")
}

export function baseFormula(formula: string): string{
  return formula.replace(/(.*) \((.*)\)/, "$1");
}

export const massOrder: SelectOption[] = [
  { value: "sIntro", label: "Wejście" },
  { value: "xGreetings", label: "Powitanie" },
  { value: "!Kyrie", label: "Kyrie" },
  { value: "!Gloria", label: "Gloria" },
  { value: "xLUP1", label: "Módlmy się" },
  { value: "xReading1", label: "Czytanie (Stary Testament)" },
  { value: "pPsalm", label: "Psalm" },
  { value: "xReading2", label: "Czytanie (Nowy Testament)" },
  { value: "pAccl", label: "Aklamacja" },
  { value: "xEvang", label: "Ewangelia" },
  { value: "xHomily", label: "Kazanie" },
  { value: "!Credo", label: "Credo" },
  { value: "xGI", label: "Modlitwa Powszechna" },
  { value: "sOffer", label: "Przygotowanie Darów" },
  { value: "!Sanctus", label: "Sanctus" },
  { value: "xTransf", label: "Przemienienie" },
  { value: "!PaterNoster", label: "Ojcze nasz" },
  { value: "!AgnusDei", label: "Agnus Dei" },
  { value: "sCommunion", label: "Komunia" },
  { value: "sAdoration", label: "Uwielbienie" },
  { value: "xLUP2", label: "Módlmy się" },
  { value: "xAnnounc", label: "Ogłoszenia" },
  { value: "xBlessing", label: "Błogosławieństwo" },
  { value: "sDismissal", label: "Zakończenie" },
];
