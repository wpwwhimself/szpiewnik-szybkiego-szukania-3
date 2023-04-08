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

/**
 * Saving song
 */
app.post("/api/save-song", (req, res) => {
  const sourcePath = "src/data/songs.json";
  const directoryPath = path.dirname(new URL(import.meta.url).pathname).substring(1);
  const allItems = JSON.parse(
    fs.readFileSync(path.join(directoryPath, `../${sourcePath}`), "utf8")
  );
  const { itemToChange, item_id } = req.body;
  allItems[item_id] = itemToChange;

  fs.writeFile(sourcePath, JSON.stringify(allItems, null, 2), (err) => {
    if (err) {
      console.error(err);
      res.status(500).send("Error saving data");
    } else {
      res.send("Data saved successfully");
    }
  });
});

/**
 * Saving ordinarius
 */
app.post("/api/save-ordinarius", (req, res) => {
  const sourcePath = "src/data/ordinarium.json";
  const directoryPath = path.dirname(new URL(import.meta.url).pathname).substring(1);
  const allItems = JSON.parse(
    fs.readFileSync(path.join(directoryPath, `../${sourcePath}`), "utf8")
  );
  const { itemToChange, item_id } = req.body;
  allItems[item_id] = itemToChange;

  fs.writeFile(sourcePath, JSON.stringify(allItems, null, 2), (err) => {
    if (err) {
      console.error(err);
      res.status(500).send("Error saving data");
    } else {
      res.send("Data saved successfully");
    }
  });
});

/**
 * Saving formulas
 */
app.post("/api/save-formula", (req, res) => {
  const sourcePath = "src/data/formulas.json";
  const directoryPath = path.dirname(new URL(import.meta.url).pathname).substring(1);
  const allItems = JSON.parse(
    fs.readFileSync(path.join(directoryPath, `../${sourcePath}`), "utf8")
  );
  const { itemToChange, item_id } = req.body;
  allItems[item_id] = itemToChange;

  fs.writeFile(sourcePath, JSON.stringify(allItems, null, 2), (err) => {
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