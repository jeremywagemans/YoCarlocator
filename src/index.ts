'use strict';

import { Request, Response } from "@types/express"

exports.handleYo = (req: Request, res: Response)  => {
    res.status(200).send("Success")
};