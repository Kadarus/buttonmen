ALTER TABLE game_player_map
ADD original_recipe VARCHAR(100)
AFTER button_id;

DROP VIEW IF EXISTS game_player_view;
CREATE VIEW game_player_view
AS SELECT
    m.game_id,
    m.player_id,
    m.button_id,
    m.original_recipe,
    m.alt_recipe,
    m.position,
    m.did_win_initiative,
    m.is_awaiting_action,
    m.n_rounds_won,
    m.n_rounds_lost,
    m.n_rounds_drawn,
    m.handicap,
    m.is_player_hidden,
    m.last_action_time,
    m.was_game_dismissed,
    m.is_button_random,
    m.has_player_accepted,
    p.name_ingame AS player_name,
    p.autopass,
    p.fire_overshooting,
    b.name AS button_name,
    g.n_target_wins
FROM game_player_map AS m
LEFT JOIN player AS p
ON m.player_id = p.id
LEFT JOIN button AS b
ON m.button_id = b.id
LEFT JOIN game AS g
ON m.game_id = g.id;
