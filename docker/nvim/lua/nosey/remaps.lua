-- When text is wrapped, move by terminal rows, not lines, unless a count is given
vim.keymap.set("n", "k", "v:count == 0 ? 'gk' : 'k'", { expr = true })
vim.keymap.set("n", "j", "v:count == 0 ? 'gj' : 'j'", { expr = true })

-- Reselect visual selection after indenting
vim.keymap.set("v", "<", "<gv")
vim.keymap.set("v", ">", ">gv")

-- Easy insertion of a trailing ; or , from insert mode
vim.keymap.set("i", ";;", "<Esc>A;")
vim.keymap.set("i", ",,", "<Esc>A,")

-- Disable annoying command line typo
vim.keymap.set("n", "q:", ":q")

-- Move visually highlighted text up and down
vim.keymap.set("v", "J", ":m '>+1<cr>gv=gv")
vim.keymap.set("v", "K", ":m '<-2<cr>gv=gv")

-- Stay in middle of screen when scrolling
vim.keymap.set("n", "<C-d>", "<C-d>zz")
vim.keymap.set("n", "<C-u>", "<C-u>zz")

-- Center viewport on match when searching
vim.keymap.set("n", "n", "nzzzv")
vim.keymap.set("n", "N", "Nzzzv")

-- Macros
vim.keymap.set("n", "Q", "@@")
vim.keymap.set("x", "Q", "<cmd>norm @q<cr>")

-- Add space around block
vim.keymap.set("v", "<leader>s", "<Esc>o<Esc>gvO<Esc>O<Esc>gvO")

-- Paste over selection, but keep original in buffer
vim.keymap.set("x", "<leader>p", [["_dP]])
