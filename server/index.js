import express from "express";
import bodyParser from "body-parser";
import fs from "fs";
import cors from "cors";
import path from "path";

const app = express();
const port = 5000;
app.use(cors());

app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

app.post("/api/save-song", (req, res) => {
  const directoryPath = path.dirname(new URL(import.meta.url).pathname).substring(1);
  const allSongs = JSON.parse(
    fs.readFileSync(path.join(directoryPath, "../src/data/songs.json"), "utf8")
  );
  const { song, song_id } = req.body;
  allSongs[song_id] = song;

  fs.writeFile("src/data/songs.json", JSON.stringify(allSongs, null, 2), (err) => {
    if (err) {
      console.error(err);
      res.status(500).send("Error saving data");
    } else {
      res.send("Data saved successfully");
    }
  });
});

app.listen(port, () => {
  console.log(`Server started on port ${port}`);
});