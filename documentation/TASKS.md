# Investigations

1. Get app in Gitlab or similar for UI & deployments
2. Setup/test LoggerInterface
3. Do we need to use DB transactions?
4. NPM warnings, ignore/fix
5. Migrations always re-run, implement rollbacks/already-done, custom or package?

# Bugs

1. Heads-up, pocket high card did not beat paired board VS lower pocket cards
2. Heads-up, folding on checked board does not complete the hand (opponent should win)

# Patches

1. Check dev env on build command, prevent on prod
2. Refresh game/table admin button, then persist DB in Docker volume
3. Watch game button (passive viewer)
4. Implement secure password validation
5. Validate unique email/username
6. Config stack/limit 
7. Remaining PHPStan errors
8. Get Symfony CLI, unit tests in container

# Features

1. Win/end game, sit n go
2. Dynamically create table, there's only 1 right now
3. Timer, players have currerntly have unlimited time to act
4. Stripe/cashier (new app/repo, API?)
5. Hand history (new Poker Reports APP?)
6. Odds/calculations (Poker Calculator APP?)
7. All-ins (No Limit Hold-em)
8. Split pots