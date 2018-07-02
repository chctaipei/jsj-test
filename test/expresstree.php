<?php

/**
 * 計算數學運算式: '1- (1 - 2)*2'
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

    function __construct($expression)
    {
        $pos1 = strpos($expression, '(', 0);
        $pos2 = false;

        if ($pos1 !== false) {
            $len = strlen($expression);
            $flag = 0;

            for ($i = $pos1+1; $i< $len; $i++) {
                if ($expression[$i] == '(') {
                    $flag++;
                } else if ($expression[$i] == ')') {
                    if ($flag == 0) {
                        $pos2 = $i;
                        break;
                    }
                    $flag--;
                }
            }

            if ($flag) {
                throw new Exception("error");
            }

            $sub = substr($expression, $pos1 + 1, $pos2 - $pos1 - 1);
            $t = new tree($sub);
            $value = $t->calc();

            $expression = substr($expression, 0, $pos1) . " $value " . substr($expression, $pos2 + 1);
        }

        $this->parse($expression);
        $this->start();
    }

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

            throw new Exception("bad token");
        }//end while
        $this->token = $token;
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
        } else {
            throw new Exception('error');
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
        if (!is_numeric($token[0])) {
            throw new Exception('error');
        }

        if ($this->len == 1) {
            $this->root = $token[0];
            return ;
        }

        $node->left = $token[0];

        if (is_numeric($token[1])) {
            throw new Exception('error');
        }
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

function calc($expression) {
    try {
       $t = new tree($expression);
       return $t->calc();
    } catch (Exception $e) {
        return null;
    }
}

// return 1
var_dump(1 == calc('(1+1*2)/3'));

// return -1
var_dump(-1 ==  calc('(-1+1* -2)/3'));

// return null
var_dump(null == calc('-1+1* - -2)/3'));

// return  3
var_dump(3 == calc('( ((-1+2) * -3)/3) + 4'));

?>

