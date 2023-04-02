import { useEffect } from "react";

declare interface SectionProps{
  title: string,
  children: string | JSX.Element | JSX.Element[],
}

export function Section({title, children}: SectionProps){
  useEffect(() => {
    document.title = `${title} | Szpiewnik Szybkiego Szukania`;
    document.getElementById("page-title")!.textContent = title;
  });

  return(
    <>
      {children}
    </>
  )
}