import "./style.css";
import { Section } from "../../components/Section";
import { useState } from "react";
import songs from "../../data/songs.json";
import { Input, Select } from "../../components/Interactives";
import { SongProps, SelectOption } from "../../types";
import { useLocation } from "react-router-dom";
// const Editor = require("react-abc");
import { Editor } from "react-abc";

export function SongEdit(){
  const title_match: string = useLocation().pathname.replace(/\/songs\/(.*)/, "$1");
  const [song, setSong] = useState<SongProps | null>(
    songs.filter(soughtSong =>
      soughtSong.title.toLocaleLowerCase().replace(/ /g, "_") === title_match
    )[0]
  );

  //todo kategorie jako lista
  //todo preferencje jako checkboxy
  //TODO ignore w data podzielony na dane stałe (kategorie) i zmienne (pieśni, listy)
  return(
    <Section title={`${song?.title} | Edycja pieśni`}>
      <h1>Parametry pieśni</h1>
      <form action="">
        <Input type="text" name="title" label="Tytuł" value={song?.title} />
        <Input type="text" name="categoryCode" label="Grupa" value={song?.categoryCode} />
        <Input type="text" name="categoryDesc" label="Mini-opis" value={song?.categoryDesc ?? ""} />
        <Input type="text" name="numberPreis" label="Numer w projektorze Preis" value={song?.numberPreis} />
        <Input type="text" name="key" label="Tonacja" value={song?.key} />
        <Input type="text" name="preferences" label="Preferencje" value={song?.preferences} />
        <Input type="TEXT" name="lyrics" label="Tekst" value={song?.lyrics ?? undefined} />
        <Input type="TEXT" name="sheetMusic" label="Nuty" value={song?.sheetMusic ?? undefined} />
        <Editor editArea="sheetMusic" />
      </form>
    </Section>
  )
}