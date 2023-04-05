import { Section } from "../../components/Section";
import "./style.css";

import songs from "../../data/songs.json";
import { SongProps } from "../../types";
import { Link } from "react-router-dom";

export function Songs(){
  const listSongs: Function = (songs: SongProps[]): JSX.Element[] => {
    return songs.map(song =>
      <Link to={`/songs/${song.title.toLocaleLowerCase().replace(/ /g, "_")}`} key={song.title}>
        {song.title}
      </Link>
    )
  }

  //todo rozbicie na poszczególne grupy pieśni
  return(
    <Section title="Lista pieśni">
      <h1>Wszystkie pieśni</h1>
      {listSongs(songs)}
    </Section>
  )
}
