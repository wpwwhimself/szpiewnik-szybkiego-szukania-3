import "./style.css";
import { Section } from "../../components/Section";
import { useState } from "react";
import songs from "../../data/songs.json";
import { Input, Select } from "../../components/Interactives";
import { SongProps, SelectOption } from "../../types";

export function SongEdit(){
  const [song, setSong] = useState<SongProps | null>(null);

  const song_list: SelectOption[] = [];
  songs.forEach((song) => {
    song_list.push({key: song.title, label: song.title})
  });

  function fillInputs(title: string){
    if(title === ""){
      setSong(null);
      return;
    }
    const targetSong: SongProps = songs.filter(song => song.title === title)[0];
    setSong(targetSong);
  }

  return(
    <Section title="Zarządzanie pieśniami">
      <h1>Parametry pieśni</h1>
      <form action="">
        <Select
          name="song_selector"
          label="Wybierz pieśń"
          onChange={ev => fillInputs(ev.target.value)}
          options={song_list}
          firstEmpty
          />

        <Input type="text" name="title" label="Tytuł" value={song?.title} />
        <Input type="text" name="group" label="Grupa" value={song?.categoryCode} /> {/* TODO lista */}
        <Input type="TEXT" name="lyrics" label="Tekst" value={song?.lyrics ?? undefined} />
      </form>
    </Section>
  )
}