import { useEffect } from "react";
import { useLocation } from "react-router-dom";

declare interface SectionProps{
  title: string,
  children: string | JSX.Element | JSX.Element[],
}

export function Section({title, children}: SectionProps){
  const loc = useLocation();

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