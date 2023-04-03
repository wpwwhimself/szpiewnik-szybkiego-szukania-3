import "./style.css";
import { Section } from "../../components/Section";

import songs from "../../data/songs.json";
import { Input, Select, SelectOption} from "../../components/Interactives";

export interface SongProps{
  title: string,
};

export function Songs(){
  const song_list: SelectOption[] = [];
  songs.forEach((song) => {
    song_list.push({key: song.title, label: song.title})
  });

  return(
    <Section title="Zarządzanie pieśniami">
      <h1>Właściwości pieśni</h1>
      <form action="">
        <Select name="song_selector" label="Tytuł" options={song_list} firstEmpty />
        <Input type="TEXT" name="lyrics" label="Tekst" />
      </form>
    </Section>
  )
}