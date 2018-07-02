<?php

/**
 * 計算: '1- (1 - 2)*2'
 */

class node
{
    public $left = null;
    public $right = null;
    public $parent = null;
    public $op = null;
}

class tree
{
    public $root;
    public $token;

    function parse($expression)
    {
        $token = [];
        $len = strlen($expression);

        $i = 0;
        while ($i < $len) {
            $t = '';

            $char = $expression[$i];

            if ($char == ' ') {
                $i++;
                continue;
            }

            if ($char == '+' || $char == '*' || $char == '/') {
                $token[] = $char;
                $i++;
                continue;
            }

            if ($char == '-') {
                if (strstr('0123456789', $expression[$i + 1])) {
                    if ($i == 0 || $expression[$i - 1] == ' ') {
                        $t = '-';
                        $i++;
                    } else {
                        $token[] = $char;
                        $i++;
                        continue;
                    }
                } else {
                    $token[] = $char;
                    $i++;
                    continue;
                }
            }

            while ($i < $len && strstr('0123456789', $expression[$i])) {
                $t .= $expression[$i];
                $i++;
            }

            if ($t !== '') {
                if ($t == '-') {
                    throw new Exception("error");
                }

                $token[] = (int) $t;
                continue;
            }
        }//end while
        $this->token = $token;
    }

    function __construct($expression)
    {
        $pos1 = strpos($expression, '(', 0);
        $pos2 = strpos($expression, ')', 0);
        if (($pos1 !== false && $pos2 == false) || ($pos1 == false && $pos2 !== false) || ($pos2 < $pos1)) {
            throw new Exception("error");
        }

        if ($pos1 != false) {
            $sub = substr($expression, $pos1 + 1, $pos2 - $pos1 - 1);
            $t = new tree($sub);
            $value = $t->calc();

            $expression = substr($expression, 0, $pos1) . " $value " . substr($expression, $pos2 + 1);
        }

        $this->parse($expression);
        $this->start();
    }

    function build($id, $node)
    {
        $token = $this->token;

        if (is_numeric($token[$id])) {
            $node->right = $token[$id];
        } else {
            throw new Exception('error');
        }

        $id++;
        if ($this->len == $id) {
            return;
        }

        $node2 = new node();
        $node2->op = $token[$id];

        if ($token[$id] == '+' || $token[$id] == '-') {
            $node = &$this->root;
            $node->parent = &$node2;
            $node2->left = &$node;
            $this->root = &$node2;
        } elseif ($token[$id] == '*' || $token[$id] == '/') {
            $node2->left = $node->right;
            $node->right = &$node2;
            $node2->parent = &$node;
        }

        $id++;
        if ($this->len == $id) {
            return;
        }

        $this->build($id, $node2);
    }

    function start()
    {
        $token = $this->token;
        $this->len = count($token);

        $node = new node();
        $node->left = $token[0];
        $node->op = $token[1];

        $this->root = &$node;
        $this->build(2, $node);
    }

    function calc($node = null)
    {
        if (!$node) {
            $node = $this->root;
        }

        if (is_numeric($node)) {
            return $node;
        }

        $left = $this->calc($node->left);
        $right = $this->calc($node->right);
        switch ($node->op) {
            case '+':
                return $left + $right;
            case '-':
                return $left - $right;
            case '*':
                return $left * $right;
            case '/':
                return $left / $right;
        }
    }
}

$expression = '1-2000* -(3- 2)*2';
$t = new tree($expression);
$x = $t->calc();

echo $expression . "\n";
echo "answer = $x\n";

?>

