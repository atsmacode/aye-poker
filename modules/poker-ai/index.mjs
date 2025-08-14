import OpenAI from 'openai';
import dotenv from 'dotenv';
import express from 'express';
import { z } from 'zod';
import { zodTextFormat } from 'openai/helpers/zod';
import cors from 'cors';
import { StatusCodes } from 'http-status-codes';

dotenv.config();

const app = express();

app
    .use(cors())
    .use(express.json());

const ai = new OpenAI({ apiKey: process.env.OPENAI_API_KEY });

const DecisionSchema = z.object({
    street: z.string(),
    pot: z.number(),
    last_action: z.number(),
    to_call: z.number(),
    stack: z.number(),
    community_cards: z.array(z.string()).default([]),
    whole_cards: z.array(z.string()).default([]),
    actions_available: z.array(z.number()).default([])
});

const DecisionResponse = z.object({
    decision: z.number(),
});

app.post('/decision', async (req, res) => {
    try {
        const {street, pot, last_action, to_call, stack, community_cards, whole_cards, actions_available} = DecisionSchema.parse(req.body);

        const system = `You are a poker decision engine.
            Return an integer corresponding to the action from the list of "Actions Available"
            Mapping: 1 = Fold, 2 = Check, 3 = Call, 4 = Bet, 5 = Raise.`;

        // Todo: position, active player count...
        const user = [
            `Street: ${street}`,
            `Pot: ${pot}`,
            `Last Action: ${last_action}`,
            `To Call: ${to_call}`,
            `Stack: ${stack}`,
            `Community Cards: [${community_cards.join(', ')}]`,
            `Whole Cards: [${whole_cards.join(', ')}]`,
            `Actions Available: [${actions_available.join(', ')}]`
        ].join('\n');
 
        // Todo: verbosity/temperature if needed
        const resAi = await ai.responses.create({
            model: 'gpt-5-nano',
            input: [
                {role: 'system', content: system},
                {role: 'user', content: user}
            ],
            text: {
                format: zodTextFormat(DecisionResponse, 'decision_response'),
                verbosity: 'low'
            }
        });

        console.log(resAi);
    
        res.send(resAi.output_text);
    } catch (error) {
        if (error instanceof z.ZodError) {
            res.status(StatusCodes.BAD_REQUEST).json(error.issues);
        }

        console.log(error);

        res.status(StatusCodes.INTERNAL_SERVER_ERROR).json(error);
    }
});


const port = Number(process.env.PORT || 3000);

app.listen(port, () => {
    console.log('Listening...')
})
