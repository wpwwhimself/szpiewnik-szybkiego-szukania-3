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