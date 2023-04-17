import { useContext, useState } from "react";
import { AuthContext } from "../App";
import { Section } from "../components/Section";
import { Button, Input } from "../components/Interactives";
import { useNavigate } from "react-router-dom";


export function Auth(){
    const navigate = useNavigate();
    const { auth, checkAuth } = useContext(AuthContext);

    const [password, setPassword] = useState("");

    const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        const { value } = event.target;
        setPassword(value);
    };

    const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        if(checkAuth(password)){
            navigate(-1);
        };
    };

    return(
        <Section title="Autoryzacja">
            <form onSubmit={handleSubmit}>
                <h2>Potwierdź uprawnienia:</h2>
                <Input type="password" name="password" label="Hasło" onChange={handleChange} />
                <div className="flex-right center">
                    <Button type="submit">Zaloguj</Button>
                </div>
            </form>
        </Section>
    )
}
