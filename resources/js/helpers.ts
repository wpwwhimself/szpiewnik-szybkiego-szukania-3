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
    .replace(/ /g, "-")
    .replace(/[,]/g, "")
}

export function baseFormula(formula: string): string{
  return formula.replace(/(.*) \((.*)\)/, "$1");
}

export const massOrder: SelectOption[] = [
  { value: "sIntro", label: "Wejście" },
  { value: "oKyrie", label: "Kyrie" },
  { value: "oGloria", label: "Gloria" },
  { value: "xLUP", label: "Módlmy się", key: 100 },
  { value: "xReading1", label: "1. czytanie" },
  { value: "pPsalm", label: "Psalm" },
  { value: "xReading2", label: "2. czytanie" },
  { value: "pAccl", label: "Aklamacja" },
  { value: "xEvang", label: "Ewangelia" },
  { value: "xHomily", label: "Kazanie" },
  { value: "oCredo", label: "Credo" },
  { value: "xGI", label: "Modlitwa Powszechna" },
  { value: "sOffer", label: "Przygotowanie Darów" },
  { value: "oSanctus", label: "Sanctus" },
  { value: "xTransf", label: "Przemienienie" },
  { value: "oPaterNoster", label: "Ojcze nasz" },
  { value: "oAgnusDei", label: "Agnus Dei" },
  { value: "sCommunion", label: "Komunia" },
  { value: "sAdoration", label: "Uwielbienie" },
  { value: "xLUP", label: "Módlmy się", key: 101 },
  { value: "xAnnounc", label: "Ogłoszenia" },
  { value: "xBlessing", label: "Błogosławieństwo" },
  { value: "sDismissal", label: "Zakończenie" },
];
