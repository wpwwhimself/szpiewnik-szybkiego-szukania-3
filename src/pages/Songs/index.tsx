import { Section } from "../../components/Section";
import "./style.css";

import songs from "../../data/songs.json";

export function Songs(){
  return(
    <Section title="Lista pieśni">
      <h1>Lista pieśni</h1>
      {songs.map(song => <div className="tile" key={song.title}>{song.title}</div>)}
    </Section>
  )
}
