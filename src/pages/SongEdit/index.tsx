import "./style.css";
import { Section } from "../../components/Section";
import { useState } from "react";
import { songs, song_categories } from "../../data";
import { Input, Select } from "../../components/Interactives";
import { SongProps, SelectOption } from "../../types";
import { useLocation } from "react-router-dom";
import { Notation } from "react-abc";
import { slugAndDePL } from "../../helpers";

export function SongEdit(){
  const title_match: string = useLocation().pathname.replace(/\/songs\/(.*)/, "$1");
  const [song, setSong] = useState<SongProps | null>(
    songs.filter(function(soughtSong: SongProps){
      return slugAndDePL(soughtSong.title) === title_match
    }
    )[0]
  );
  const song_categories_proc: SelectOption[] = [];
  song_categories.forEach(item => song_categories_proc.push({key: item.id, label: item.kategoria}));

  //todo preferencje jako checkboxy
  //TODO ignore w data podzielony na dane stałe (kategorie) i zmienne (pieśni, listy)
  return(
    <Section title={`${song?.title} | Edycja pieśni`}>
      <h1>Parametry pieśni</h1>
      <form action="">
        <div className="grid-3">
          <Input type="text" name="title" label="Tytuł" value={song?.title} />
          <Select name="categoryCode" label="Grupa" value={3} options={song_categories_proc} />
          <Input type="text" name="categoryDesc" label="Mini-opis" value={song?.categoryDesc ?? ""} />
          <Input type="text" name="numberPreis" label="Numer w projektorze Preis" value={song?.numberPreis} />
          <Input type="text" name="key" label="Tonacja" value={song?.key} />
          <Input type="text" name="preferences" label="Preferencje" value={song?.preferences} />
        </div>
        <div className="grid-2">
          <Input type="TEXT" name="lyrics" label="Tekst" value={song?.lyrics ?? undefined} />
          <Input type="TEXT" name="sheetMusic" label="Nuty" value={song?.sheetMusic ?? undefined} />
        </div>
        <div className="flex-right center">
          <Notation notation={song?.sheetMusic ?? ""} />
        </div>
      </form>
    </Section>
  )
}