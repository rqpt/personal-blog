local api = vim.api
local fn = vim.fn

local ag = api.nvim_create_augroup
local au = api.nvim_create_autocmd

-- make $ part of the keyword for php.
vim.api.nvim_exec(
    [[
autocmd FileType php set iskeyword+=$
]],
    false
)

-- Highlight yanked text
au("TextYankPost", {
    group = ag("yank_highlight", {}),
    pattern = "*",
    callback = function()
        vim.highlight.on_yank({ higroup = "IncSearch", timeout = 175 })
    end,
})

local augroups = {}

-- When yanking, dont move the cursor position
augroups.yankpost = {
    save_cursor_position = {
        event = { "VimEnter", "CursorMoved" },
        pattern = "*",
        callback = function()
            cursor_pos = fn.getpos(".")
        end,
    },

    highlight_yank = {
        event = "TextYankPost",
        pattern = "*",
        callback = function()
            vim.highlight.on_yank({ higroup = "IncSearch", timeout = 400, on_visual = true })
        end,
    },

    yank_restore_cursor = {
        event = "TextYankPost",
        pattern = "*",
        callback = function()
            local cursor = fn.getpos(".")
            if vim.v.event.operator == "y" then
                fn.setpos(".", cursor_pos)
            end
        end,
    },
}

for group, commands in pairs(augroups) do
    local augroup = vim.api.nvim_create_augroup("AU_" .. group, { clear = true })

    for _, opts in pairs(commands) do
        local event = opts.event
        opts.event = nil
        opts.group = augroup
        vim.api.nvim_create_autocmd(event, opts)
    end
end
