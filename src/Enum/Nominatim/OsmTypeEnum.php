<?php

namespace GeoTool\Enum\Nominatim;

enum OsmTypeEnum: string
{
    case FULL_NODE     = "node";
    case FULL_WAY      = "way";
    case FULL_RELATION = "relation";

    case SHORT_NODE     = "N";
    case SHORT_WAY      = "W";
    case SHORT_RELATION = "R";
}
