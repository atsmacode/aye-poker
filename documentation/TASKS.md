# Investigations

1. Get app in Gitlab or similar for UI & deployments
2. Setup/test LoggerInterface
    * 30/03/25 Logging to var/log/dev.log setup
3. Do we need to use DB transactions?
4. NPM warnings, ignore/fix
5. Migrations always re-run, implement rollbacks/already-done, custom or package?

# Bugs

1. Heads-up, pocket high card ace did not beat paired board VS lower pocket cards
2. Heads-up, folding on checked board does not complete the hand (opponent should win)
3. If Player is saved but User/UserPlayer fails, it doesn't rollback (transactions, argument to use shared DB connection)

# Patches

1. Check dev env on build command, prevent on prod
2. Refresh game/table admin button, then persist DB in Docker volume
3. Watch game button (passive viewer)
4. Implement secure password validation
5. Validate unique email/username
    * 29/03/25 Checking unique e-mail, showing errors on screen
    * 30/03/25 Validating unique player name
6. Config stack/limit 
7. Remaining PHPStan errors
8. Get Symfony CLI, unit tests in container
9. Encode auto-generated password (contains special chars)
10. Use Symfony doctrine to create poker_game DB, remove PDO wrapper
11. Stop using in-app controllers with Request/Response, use interfaces/Exceptions
    * 31/03/25 Using services instead
12. Too many duplicated factories for dependencies in poker-game

# Features

1. Win/end game, sit n go
2. Dynamically create table, there's only 1 right now
3. Timer, players have currerntly have unlimited time to act
4. Stripe/cashier (new app/repo, API?)
5. Hand history (new Poker Reports APP?)
6. Odds/calculations (Poker Calculator APP?)
7. All-ins (No Limit Hold-em)
8. Split pots
9. New Game: create new table, select player count, test/real mode, open to join, starts when all click 'ready', select blind level, choose game format
10. Side pots