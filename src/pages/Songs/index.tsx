import "./style.css";
import { Section } from "../../components/Section";

import songs from "../../data/songs.json";

export interface SongProps{
  title: string,
};

export function Songs(){
  return(
    <Section title="Zarządzanie pieśniami">
      <h1>Lista pieśni</h1>
      <div>
      {songs.map((song, ind) => 
        <p key={ind}>
          {song.title}
        </p>
      )}
      </div>
    </Section>
  )
}