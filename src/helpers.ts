import { SelectOption } from "./types";

export function slugAndDePL(string: string): string{
  return string
    .replace(/ą/g, "a")
    .replace(/ć/g, "c")
    .replace(/ę/g, "e")
    .replace(/ł/g, "l")
    .replace(/ń/g, "n")
    .replace(/ó/g, "o")
    .replace(/ś/g, "s")
    .replace(/[źż]/g, "z")
    .toLocaleLowerCase()
    .replace(/ /g, "-")
    .replace(/[,]/g, "")
}

export const massOrder: SelectOption[] = [
  { value: "sIntro", label: "Wejście" },
  { value: "oKyrie", label: "Kyrie" },
  { value: "oGloria", label: "Gloria" },
  { value: "pPsalm", label: "Psalm" },
  { value: "pAccl", label: "Aklamacja" },
  { value: "xEvang", label: "Ewangelia" },
  { value: "xHomily", label: "Kazanie" },
  { value: "oCredo", label: "Credo" },
  { value: "xGI", label: "Modlitwa Powszechna" },
  { value: "sOffer", label: "Przygotowanie Darów" },
  { value: "oSanctus", label: "Sanctus" },
  { value: "xTransf", label: "Przemienienie" },
  { value: "xPaterNoster", label: "Ojcze nasz" },
  { value: "oAgnusDei", label: "Agnus Dei" },
  { value: "sCommunion", label: "Komunia" },
  { value: "sAdoration", label: "Uwielbienie" },
  { value: "sDismissal", label: "Zakończenie" },
];