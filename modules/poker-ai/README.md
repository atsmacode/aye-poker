# Poker AI

An API for various AI Poker features. Includes:

- AI opponent decisions

Example Request:

POST http://localhost:3000/decision

```
{
  "street": "Pre flop",
  "pot": 0,
  "last_action": "Bet",
  "to_call": 100,
  "stack": 1500,
  "community_cards": [],
  "whole_cards": ["7D", "7H"],
  "actions_available": [1, 3, 5]
}
```

Expected Response:

```
{
  "decision": 3
}
```

3 = Call or 5 = Raise

In future:

- Poker coach / "How to play poker"