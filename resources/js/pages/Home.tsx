import { Link } from "react-router-dom";
import { Section } from "../components/Section";
import { sets } from "../data";
import { slugAndDePL } from "../helpers";
import React, { Fragment } from "react";

const formulas = sets
  .map(item => item.formulaName)
  .filter((name, index, array) => array.indexOf(name) === index);

export function Home(){
  return(
    <Section title="Pulpit">
      <h1>Załaduj mszę</h1>
      <div>
      {formulas.map((formula, i) =>
        <Fragment key={i}>
          <h2>{formula}</h2>
          <div className="flex-right center wrap">
          {sets.filter(el => el.formulaName === formula).map((set, i) =>
            <Link key={i} to={`sets/show/${slugAndDePL(set.name)}`}>
              {set.name}
            </Link>
          )}
          </div>
        </Fragment>
      )}
      </div>
    </Section>
  );
}
